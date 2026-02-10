<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return $this->responseSuccessWithData('user data', ['users' => User::with(['profile', 'role', 'attendances' => function ($query) use ($request) {
            $query->where('date', $request->query('date', date('Y-m-d')));
        }, 'leaves' => function ($query) use ($request, $project) {
            $query->where('start_date', '<=', $request->query('date', date('Y-m-d')))->where('to_date', '>=', $request->query('date', date('Y-m-d')))->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('project_id', $project->id)->where('status', 2);
        }])->where('project_id', $project->id)->when($request->get('search') != "" || $request->query('search') != null, function ($query) use ($request) {
            $query->where('name', 'ilike', '%' . $request->query('name') . '%');
        })->get(), 'project' => $project]);
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
        $user->load(['attendances' => function ($query) use ($request) {
            $query->whereBetween('created_at', [Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01'), Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01')->addMonth()]);
        }, 'leaves' => function ($query) use ($request) {
            $query->whereBetween('start_date', [Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01'), Carbon::parse(date('Y') . '-' . $request->query('month', date('m')) . '-01')->addMonth()])->where('status', 2);
        }]);
        return $this->responseSuccessWithData('data user', $user);
    }
}
