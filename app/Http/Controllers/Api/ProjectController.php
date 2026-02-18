<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Validator;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::query()
            ->when(Auth::user()->hasPermission('view-other-project'), function (Builder $query) {
                $projectArr = [];
                $projectArr[] = Auth::user()->project_id;
                array_push($projectArr, ...Auth::user()->projects->pluck('id')->toArray());
                $query->whereIn('id', $projectArr);
            }, function (Builder $query) {
                $query->when(Auth::user()->hasPermission('view-own-project'), function (Builder $query) {
                    $query->where('id', Auth::user()->project_id);
                });
            })
            ->get();
        return $this->responseSuccessWithData('data project', $projects);
    }

    public function indexPublic(): JsonResponse
    {
        $projects = Project::query()
            ->select(['id', 'name'])
            ->get();
        return $this->responseSuccessWithData('data project', $projects);
    }

    public function getUsers(Project $project, Request $request): JsonResponse
    {
        $month = Carbon::parse($request->query('date', date('Y-m-d')));
        $monthStart = $month->copy()->startOfMonth()->toDateString();
        $monthEnd   = $month->copy()->addMonth()->startOfMonth()->toDateString();

        $workDateExpr = "COALESCE(date, (created_at AT TIME ZONE 'Asia/Jakarta')::date)";

        $dailyIn = DB::table('attendances')
            ->selectRaw("
                user_id,
                project_id,
                $workDateExpr AS work_date,
                MIN(created_at) AS check_in_at
            ")
            ->where('type', 'in')
            ->where('project_id', $project->id)
            ->whereRaw("$workDateExpr >= ?", [$monthStart])
            ->whereRaw("$workDateExpr < ?",  [$monthEnd])
            ->groupByRaw("user_id, project_id, $workDateExpr");

        $winnerRajin = DB::query()
            ->fromSub($dailyIn, 'di')
            ->join('users as u', 'u.id', '=', 'di.user_id')
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM(
                    GREATEST(
                        0,
                        EXTRACT(EPOCH FROM ((work_date::timestamp + (?::time)) - check_in_at)) / 60.0
                    )
                ) AS total_early_minutes
            ", [$project->check_in_time])
            ->where('u.role_id', '!=', 2)
            ->groupBy('u.id')
            ->orderByDesc('total_early_minutes')
            ->first();

        $winnerTerlambat = DB::query()
            ->fromSub($dailyIn, 'di')
            ->join('users as u', 'u.id', '=', 'di.user_id')
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM(
                    GREATEST(
                        0,
                        EXTRACT(EPOCH FROM (check_in_at - (work_date::timestamp + (?::time)))) / 60.0
                    )
                ) AS total_late_minutes_over_15
            ", [$project->check_in_time])
            ->where('u.role_id', '!=', 2)
            ->groupBy('u.id')
            ->orderByDesc('total_late_minutes_over_15')
            ->first();

        $expectedWorkdays = collect(CarbonPeriod::create($monthStart, Carbon::parse($monthEnd)->subDay()))
            ->filter(fn(CarbonInterface $d) => !$d->isWeekend())
            ->count();

        $winnerTidakAbsen = DB::table('users as u')
            ->leftJoinSub($dailyIn, 'di', function ($join) {
                $join->on('di.user_id', '=', 'u.id');
            })
            ->selectRaw("
                u.id as user_id,
                u.name,
                (? - COUNT(di.work_date)) AS absent_days
            ", [$expectedWorkdays])
            ->where('u.project_id', $project->id)
            ->where('u.role_id', '!=', 2)
            ->groupBy('u.id')
            ->orderByDesc('absent_days')
            ->first();

        $workDateExpr = "COALESCE(a.date, (a.created_at AT TIME ZONE 'Asia/Jakarta')::date)";

        $winnerPulangCepat = DB::table('attendances as a')
            ->join('users as u', 'u.id', '=', 'a.user_id')
            ->where('u.role_id', '!=', 2)
            ->where('a.project_id', $project->id)
            ->where('a.type', 'out')
            ->where('a.status', 'Pulang Cepat')
            ->whereRaw("$workDateExpr >= ?", [$monthStart])
            ->whereRaw("$workDateExpr <  ?", [$monthEnd])
            ->selectRaw('u.id as user_id, u.name, COUNT(*) as total_pulang_cepat')
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_pulang_cepat')
            ->first();

        $base = DB::table('leaves as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->where('l.project_id', $project->id)
            ->where('u.role_id', '!=', 2)
            ->where('l.status', 2)
            ->whereDate('l.start_date', '<=', $monthEnd)
            ->whereDate('l.to_date', '>=', $monthStart);

        $daysExpr = "GREATEST(
                0,
                (LEAST(l.to_date, ?::date) - GREATEST(l.start_date, ?::date) + 1)
              )";

        $winnerDinasLuar = (clone $base)
            ->whereIn('l.type', ['Perjalanan Dinas', 'Dinas Luar'])
            ->where('u.role_id', '!=', 2)
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM($daysExpr) as total_days
            ", [$monthEnd, $monthStart])
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_days')
            ->first();

        $winnerIzin = (clone $base)
            ->whereIn('l.type', ['Izin Lainnya', 'Lainnya'])
            ->where('u.role_id', '!=', 2)
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM($daysExpr) as total_days
            ", [$monthEnd, $monthStart])
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_days')
            ->first();

        $winnerSakit = (clone $base)
            ->where('l.type', 'Sakit')
            ->where('u.role_id', '!=', 2)
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM($daysExpr) as total_days
            ", [$monthEnd, $monthStart])
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_days')
            ->first();

        $predikat = [
            'paling_rajin' => $winnerRajin?->name,
            'sering_terlambat' => $winnerTerlambat?->name,
            'sering_tidak_absen' => $winnerTidakAbsen?->name,
            'sering_pulang_cepat' => $winnerPulangCepat?->name,
            'sering_dinas_luar' => $winnerDinasLuar?->name,
            'sering_izin' => $winnerIzin?->name,
            'sering_sakit' => $winnerSakit?->name,
        ];

        return $this->responseSuccessWithData('user data', [
            'users' => User::query()
                ->with([
                    'profile',
                    'role',
                    'attendances' => function ($query) use ($request) {
                        $query
                            ->where('date', $request->query('date', date('Y-m-d')));
                    },
                    'leaves' => function ($query) use ($request, $project) {
                        $query
                            ->where('start_date', '<=', $request->query('date', date('Y-m-d')))
                            ->where('to_date', '>=', $request->query('date', date('Y-m-d')))
                            ->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])
                            ->where('project_id', $project->id)
                            ->where('status', 2);
                    }
                ])
                ->where('project_id', $project->id)
                ->when($request->get('search') != "" || $request->query('search') != null, function ($query) use ($request) {
                    $query
                        ->where('name', 'ilike', '%' . $request->query('name') . '%');
                })
                ->get(),
            'project' => $project,
            'predikat' => $predikat
        ]);
    }

    public function listAttendance(Project $project, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'date' => 'nullable|date',
        ]);
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();

        $user = User::query()
            ->with(['profile', 'position', 'attendances' => function ($query) use ($project, $validated) {
                $query->where('project_id', $project->id)
                    ->whereBetween('date', [date('Y-m-01'), date('Y-m-t')]);
            }, 'leaves' => function ($query) use ($project, $validated) {
                $query->where('start_date', '>=', date('Y-m-01'))
                    ->where('to_date', '<=', date('Y-m-t'))
                    ->where('project_id', $project->id);
            }])
            ->where('id', $validated['user_id'])
            ->first();

        $datePeriod = collect(CarbonPeriod::create(date('Y-m-01'), date('Y-m-t'))->toArray())
            ->map(function (CarbonInterface $date) {
                return $date->format('Y-m-d');
            });

        $report = collect();

        foreach ($datePeriod as $date) {
            $leave = $user->leaves->where('start_date', '<=', $date)
                ->where('to_date', '>=', $date)
                ->whereIn('type', ['Perjalanan Dinas', 'Sakit', 'Izin Lainnya', 'Dinas Luar', 'Lainnya'])
                ->first();

            if ($leave) {
                $report->push([
                    'date' => $date,
                    'in' => $leave->type,
                    'out' => $leave->reason,
                ]);

                //                continue;
            } else {
                $attendance = $user->attendances->where('date', $date);
                $report->push([
                    'date' => $date,
                    'in' => $attendance->where('type', '=', 'in')->first()?->created_at->format('H:i'),
                    'out' => $attendance->where('type', '=', 'out')->first()?->created_at->format('H:i'),
                ]);
            }
        }

        $reportCount = [
            'sum_tepat_waktu' => $user->attendances->where('status', '=', 'Tepat Waktu')->count(),
            'sum_terlambat' => $user->attendances->where('status', '=', 'Terlambat')->count(),
            'sum_pulang_cepat' => $user->attendances->where('status', '=', 'Pulang Cepat')->count(),
            'sum_dinas_luar' => $user->leaves->whereIn('type', ['Dinas Luar', 'Perjalanan Dinas'])->count(),
            'sum_sakit' => $user->leaves->where('type', '=', 'Sakit')->count(),
            'sum_lainnya' => $user->leaves->whereIn('type', ['Lainnya', 'Izin Lainnya'])->count(),
        ];

        $user->setAttribute('report', $report);
        $user->setAttribute('report_count', $reportCount);
        return $this->responseSuccessWithData('data user', $user);
    }

    public function selfReport(User $user, Request $request): JsonResponse
    {
        $user->load([
            'attendances' => function ($query) use ($request) {
                $query
                    ->whereBetween('created_at', [
                        Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01'),
                        Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01')->addMonth()
                    ]);
            },
            'leaves' => function ($query) use ($request) {
                $query
                    ->whereBetween('start_date', [
                        Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01'),
                        Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01')->addMonth()
                    ])
                    ->where('status', 2);
            },
            'project'
        ]);

        $month = Carbon::parse($request->query('date', date('Y-m-d')));
        $monthStart = $month->copy()->startOfMonth()->toDateString();
        $monthEnd   = $month->copy()->addMonth()->startOfMonth()->toDateString();

        $workDateExpr = "COALESCE(date, (created_at AT TIME ZONE 'Asia/Jakarta')::date)";

        $dailyIn = DB::table('attendances')
            ->selectRaw("
                user_id,
                project_id,
                $workDateExpr AS work_date,
                MIN(created_at) AS check_in_at
            ")
            ->where('type', 'in')
            ->where('project_id', $user->project_id)
            ->whereRaw("$workDateExpr >= ?", [$monthStart])
            ->whereRaw("$workDateExpr < ?",  [$monthEnd])
            ->groupByRaw("user_id, project_id, $workDateExpr");

        $winnerRajin = DB::query()
            ->fromSub($dailyIn, 'di')
            ->join('users as u', 'u.id', '=', 'di.user_id')
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM(
                    GREATEST(
                        0,
                        EXTRACT(EPOCH FROM ((work_date::timestamp + (?::time)) - check_in_at)) / 60.0
                    )
                ) AS total_early_minutes
            ", [$user->project->check_in_time])
            ->where('u.role_id', '!=', 2)
            ->groupBy('u.id')
            ->orderByDesc('total_early_minutes')
            ->first();

        $winnerTerlambat = DB::query()
            ->fromSub($dailyIn, 'di')
            ->join('users as u', 'u.id', '=', 'di.user_id')
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM(
                    GREATEST(
                        0,
                        EXTRACT(EPOCH FROM (check_in_at - (work_date::timestamp + (?::time)))) / 60.0
                    )
                ) AS total_late_minutes_over_15
            ", [$user->project->check_in_time])
            ->where('u.role_id', '!=', 2)
            ->groupBy('u.id')
            ->orderByDesc('total_late_minutes_over_15')
            ->first();

        $expectedWorkdays = collect(CarbonPeriod::create($monthStart, Carbon::parse($monthEnd)->subDay()))
            ->filter(fn(CarbonInterface $d) => !$d->isWeekend())
            ->count();

        $winnerTidakAbsen = DB::table('users as u')
            ->leftJoinSub($dailyIn, 'di', function ($join) {
                $join->on('di.user_id', '=', 'u.id');
            })
            ->selectRaw("
                u.id as user_id,
                u.name,
                (? - COUNT(di.work_date)) AS absent_days
            ", [$expectedWorkdays])
            ->where('u.project_id', $user->project_id)
            ->where('u.role_id', '!=', 2)
            ->groupBy('u.id')
            ->orderByDesc('absent_days')
            ->first();

        $workDateExpr = "COALESCE(a.date, (a.created_at AT TIME ZONE 'Asia/Jakarta')::date)";

        $winnerPulangCepat = DB::table('attendances as a')
            ->join('users as u', 'u.id', '=', 'a.user_id')
            ->where('u.role_id', '!=', 2)
            ->where('a.project_id', $user->project_id)
            ->where('a.type', 'out')
            ->where('a.status', 'Pulang Cepat')
            ->whereRaw("$workDateExpr >= ?", [$monthStart])
            ->whereRaw("$workDateExpr <  ?", [$monthEnd])
            ->selectRaw('u.id as user_id, u.name, COUNT(*) as total_pulang_cepat')
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_pulang_cepat')
            ->first();

        $base = DB::table('leaves as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->where('l.project_id', $user->project_id)
            ->where('u.role_id', '!=', 2)
            ->where('l.status', 2)
            ->whereDate('l.start_date', '<=', $monthEnd)
            ->whereDate('l.to_date', '>=', $monthStart);

        $daysExpr = "GREATEST(
                0,
                (LEAST(l.to_date, ?::date) - GREATEST(l.start_date, ?::date) + 1)
              )";

        $winnerDinasLuar = (clone $base)
            ->whereIn('l.type', ['Perjalanan Dinas', 'Dinas Luar'])
            ->where('u.role_id', '!=', 2)
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM($daysExpr) as total_days
            ", [$monthEnd, $monthStart])
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_days')
            ->first();

        $winnerIzin = (clone $base)
            ->whereIn('l.type', ['Izin Lainnya', 'Lainnya'])
            ->where('u.role_id', '!=', 2)
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM($daysExpr) as total_days
            ", [$monthEnd, $monthStart])
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_days')
            ->first();

        $winnerSakit = (clone $base)
            ->where('l.type', 'Sakit')
            ->where('u.role_id', '!=', 2)
            ->selectRaw("
                u.id as user_id,
                u.name,
                SUM($daysExpr) as total_days
            ", [$monthEnd, $monthStart])
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total_days')
            ->first();

        $predikat = [
            'paling_rajin' => $winnerRajin?->name,
            'sering_terlambat' => $winnerTerlambat?->name,
            'sering_tidak_absen' => $winnerTidakAbsen?->name,
            'sering_pulang_cepat' => $winnerPulangCepat?->name,
            'sering_dinas_luar' => $winnerDinasLuar?->name,
            'sering_izin' => $winnerIzin?->name,
            'sering_sakit' => $winnerSakit?->name,
        ];

        $user->setAttribute('predikat', $predikat);

        return $this->responseSuccessWithData('data user', $user);
    }
}
