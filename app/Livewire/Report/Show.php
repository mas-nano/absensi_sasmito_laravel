<?php

namespace App\Livewire\Report;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\MinusMultiply;
use App\Models\OvertimeLimit;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class Show extends Component
{
    public ?Project $project;

    public $dates;
    // public $users;
    public $show;

    public function mount(Project $project)
    {
        $isLeavePage = request()->query('leave-page', null);
        if ($isLeavePage) {
            $this->show = 'leave';
        } else {
            $this->show = 'attendance';
        }
        $project->load('users.role');
        $this->project = $project;
        $this->dates = [
            date('Y-m-01'),
            date('Y-m-t')
        ];
        // $this->users = User::with(['profile', 'attendances' => function ($query) {
        //     $query->where('project_id', $this->project->id)
        //         ->where('date', '>=', $this->dates ? $this->dates[0] : Carbon::now()->format('Y-m-01'))
        //         ->where('date', '<=',  $this->dates ? $this->dates[1] : Carbon::now()->format('Y-m-t'));
        // }, 'leaves' => function ($query) {
        //     $query->where('project_id', $this->project->id)->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('status', 2);
        // }])->where('project_id', $this->project->id)->where('role_id', '!=', 2)->get();
    }

    public function updating($property, $value)
    {
        if ($property == 'dates') {
            if (is_array($value)) {
                $this->dates = array_map(function ($val) {
                    return Carbon::parse($val)->setTimezone('Asia/Jakarta')->toDateString();
                }, $value);
                // dd($this->dates);
            }
            // $this->users = User::with(['profile', 'attendances' => function ($query) {
            //     $query->where('project_id', $this->project->id)
            //         ->where('date', '>=', $this->dates ? $this->dates[0] : Carbon::now()->format('Y-m-01'))
            //         ->where('date', '<=',  $this->dates ? $this->dates[1] : Carbon::now()->format('Y-m-t'));
            // }, 'leaves' => function ($query) {
            //     $query->where('project_id', $this->project->id)->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('status', 2);
            // }])->where('project_id', $this->project->id)->where('role_id', '!=', 2)->get();
        }
    }

    public function render()
    {
        $listDates = [];
        if ($this->dates) {
            $listDates = array_map(function ($val) {
                return $val->setTimezone('Asia/Jakarta')->toDateString();
            }, CarbonPeriod::create($this->dates[0], $this->dates[1])->toArray());
        } else {
            for ($i = 1; $i <= date('t'); $i++) {
                array_push($listDates, date('Y-m-' . str_pad($i, 2, "0", STR_PAD_LEFT)));
            }
        }

        $users = User::with(['profile', 'attendances' => function ($query) {
            $query->where('project_id', $this->project->id)
                ->where('date', '>=', $this->dates ? Carbon::parse($this->dates[0])->subDay()->setTimezone('Asia/Jakarta')->toDateString() : Carbon::now()->format('Y-m-01'))
                ->where('date', '<=', $this->dates ? Carbon::parse($this->dates[1])->setTimezone('Asia/Jakarta')->toDateString() : Carbon::now()->format('Y-m-t'));
        }, 'leaves' => function ($query) {
            $query->where('project_id', $this->project->id)->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('status', 2);
        }])->where('project_id', $this->project->id)->where('role_id', '!=', 2)->get();
        // if ($this->dates[1] != date('Y-m-29')) {
        //     dd($users, $listDates, $this->dates);
        // }
        $overtimeLimits = OvertimeLimit::query()
            ->where('project_id', $this->project->id)
            ->orderBy('multiply', 'asc')
            ->get();

        $timeLimit = Setting::query()
            ->where('field', 'time')
            ->first()
            ?->value ?? '00:00';

        foreach ($users as $user) {
            $reports = [];
            foreach ($listDates as $value) {
                $multiply = 0;
                $attendanceIn = $user->attendances->where('date', $value)->where('type', 'in')->first();
                $attendanceOut = $user->attendances->where('date', $value)->where('type', 'out')->first();
                if ($attendanceIn && $attendanceOut) {
                    $multiply = $overtimeLimits->first()?->multiply ?? 0;
                    // $overtimeExist = Overtime::query()
                    //     ->where('user_id', $user->id)
                    //     ->where('project_id', $this->project->id)
                    //     ->where('date', $value)
                    //     ->first();

                    // if ($overtimeExist) {
                    foreach ($overtimeLimits as $key => $overtimeLimit) {
                        if ($timeLimit > '00:00') {
                            if (
                                Carbon::parse($overtimeLimit->check_out_time_limit) <=
                                Carbon::parse($timeLimit) &&
                                Carbon::parse($overtimeLimit->check_out_time_limit) >=
                                Carbon::parse('00:00')
                            ) {
                                if (
                                    (Carbon::parse($attendanceOut->created_at) <=
                                        Carbon::parse($value . ' ' . $overtimeLimit->check_out_time_limit)->addDay() &&
                                        collect(json_decode($overtimeLimit->days, true))->contains(Carbon::parse($value)->dayOfWeek)) ||
                                    $overtimeLimits->count() ==
                                    $key + 1
                                ) {
                                    // $console->writeln('tengah-tengah ' . $o->multiply . ' ' . $o->id);
                                    $minusMultiplies = MinusMultiply::query()
                                        ->where('project_id', $this->project->id)
                                        ->where('minus_time_limit', '<', $attendanceIn->created_at->format('H:i'))
                                        ->when($attendanceIn->created_at->format('H:i') > $timeLimit, function ($query) use ($timeLimit) {
                                            return $query
                                                ->where('minus_time_limit', '>=', $timeLimit);
                                        })
                                        ->whereJsonContains('days', Carbon::parse($value)->dayOfWeek)
                                        ->orderBy('minus', 'desc')
                                        ->first();

                                    // Ambil out dari hari sebelum date berjalan. Cek apakah out nya lebih dari jam 1.
                                    // Jika iya, cek lagi apakah in pada date berjalan lebih dari out kemarin + 8 jam.
                                    // Jika iya, minus multiply.
                                    // 8 jam dan jam 1 ambil dari DB di tabel project
                                    $attendanceOutYesterday = $user->attendances->where('date', Carbon::parse($value)->subDay()->toDateString())->where('type', 'out')->first();
                                    if (
                                        $this->project->start_tolerance_overtime !== null &&
                                        $this->project->duration_tolerance_overtime !== null &&
                                        $attendanceOutYesterday &&
                                        $attendanceOutYesterday->created_at->format('H:i') > $this->project->start_tolerance_overtime
                                    ) {
                                        if (!Carbon::parse($attendanceIn->created_at)->gt(Carbon::parse($attendanceOutYesterday->created_at)->addHours($this->project->duration_tolerance_overtime))) {
                                            $minusMultiplies = null;
                                        }
                                    }
                                    $minus = 0;
                                    if (Carbon::parse($value)->toDateString() != '2026-02-05') {
                                        $minus = $minusMultiplies?->minus;
                                    }
                                    $multiply = $overtimeLimit->multiply - $minus;
                                    if ($multiply < 0) $multiply = 0;
                                    break;
                                }
                            } else {
                                if (
                                    (Carbon::parse($attendanceOut->created_at) <=
                                        Carbon::parse($value . ' ' . $overtimeLimit->check_out_time_limit) &&
                                        collect(json_decode($overtimeLimit->days, true))->contains(Carbon::parse($value)->dayOfWeek)) ||
                                    $overtimeLimits->count() ==
                                    $key + 1
                                ) {
                                    // $console->writeln('ga di tengah ' . $o->multiply . ' ' . $o->id);
                                    $minusMultiplies = MinusMultiply::query()
                                        ->where('project_id', $this->project->id)
                                        ->where('minus_time_limit', '<', $attendanceIn->created_at->format('H:i'))
                                        ->when($attendanceIn->created_at->format('H:i') > $timeLimit, function ($query) use ($timeLimit) {
                                            return $query
                                                ->where('minus_time_limit', '>=', $timeLimit);
                                        })
                                        ->whereJsonContains('days', Carbon::parse($value)->dayOfWeek)
                                        ->orderBy('minus', 'desc')
                                        ->first();

                                    // Ambil out dari hari sebelum date berjalan. Cek apakah out nya lebih dari jam 1.
                                    // Jika iya, cek lagi apakah in pada date berjalan lebih dari out kemarin + 8 jam.
                                    // Jika iya, minus multiply.
                                    // 8 jam dan jam 1 ambil dari DB di tabel project
                                    $attendanceOutYesterday = $user->attendances->where('date', Carbon::parse($value)->subDay()->toDateString())->where('type', 'out')->first();
                                    if (
                                        $this->project->start_tolerance_overtime !== null &&
                                        $this->project->duration_tolerance_overtime !== null &&
                                        $attendanceOutYesterday &&
                                        $attendanceOutYesterday->created_at->format('H:i') > $this->project->start_tolerance_overtime
                                    ) {
                                        if (!Carbon::parse($attendanceIn->created_at)->gt(Carbon::parse($attendanceOutYesterday->created_at)->addHours($this->project->duration_tolerance_overtime))) {
                                            $minusMultiplies = null;
                                        }
                                    }
                                    $minus = 0;
                                    if (Carbon::parse($value)->toDateString() != '2026-02-05') {
                                        $minus = $minusMultiplies?->minus;
                                    }
                                    $multiply = $overtimeLimit->multiply - $minus;
                                    if ($multiply < 0) $multiply = 0;
                                    break;
                                }
                            }
                        } else {
                            if (
                                (Carbon::parse($attendanceOut->created_at) <=
                                    Carbon::parse($value . ' ' . $overtimeLimit->check_out_time_limit) &&
                                    collect(json_decode($overtimeLimit->days, true))->contains(Carbon::parse($value)->dayOfWeek)) ||
                                $overtimeLimits->count() ==
                                $key + 1
                            ) {
                                // $console->writeln('kur den timelimit ' . $o->multiply . ' ' . $o->id);
                                $minusMultiplies = MinusMultiply::query()
                                    ->where('project_id', $this->project->id)
                                    ->where('minus_time_limit', '<', $attendanceIn->created_at->format('H:i'))
                                    ->when($attendanceIn->created_at->format('H:i') > $timeLimit, function ($query) use ($timeLimit) {
                                        return $query
                                            ->where('minus_time_limit', '>=', $timeLimit);
                                    })
                                    ->whereJsonContains('days', Carbon::parse($value)->dayOfWeek)
                                    ->orderBy('minus', 'desc')
                                    ->first();

                                // Ambil out dari hari sebelum date berjalan. Cek apakah out nya lebih dari jam 1.
                                // Jika iya, cek lagi apakah in pada date berjalan lebih dari out kemarin + 8 jam.
                                // Jika iya, minus multiply.
                                // 8 jam dan jam 1 ambil dari DB di tabel project
                                $attendanceOutYesterday = $user->attendances->where('date', Carbon::parse($value)->subDay()->toDateString())->where('type', 'out')->first();
                                if (
                                    $this->project->start_tolerance_overtime !== null &&
                                    $this->project->duration_tolerance_overtime !== null &&
                                    $attendanceOutYesterday &&
                                    $attendanceOutYesterday->created_at->format('H:i') > $this->project->start_tolerance_overtime
                                ) {
                                    if (!Carbon::parse($attendanceIn->created_at)->gt(Carbon::parse($attendanceOutYesterday->created_at)->addHours($this->project->duration_tolerance_overtime))) {
                                        $minusMultiplies = null;
                                    }
                                }
                                $minus = 0;
                                if (Carbon::parse($value)->toDateString() != '2026-02-05') {
                                    $minus = $minusMultiplies?->minus;
                                }
                                $multiply = $overtimeLimit->multiply - $minus;
                                if ($multiply < 0) $multiply = 0;
                                break;
                            }
                        }
                    }
                    // }
                    if ($user->hasPermission('create-free-attendance')) {
                        $multiply = $overtimeLimits->where('multiply', '>', 0)->first()?->multiply ?? 0;
                    }
                }

                if ($user->leaves->where('start_date', '<=', $value)->where('to_date', '>=', $value)->where('type', 'Dinas Luar')->where('status', 2)->first()) {
                    $multiply = $overtimeLimits->where('multiply', '>', 0)->first()?->multiply ?? 0;
                }

                $reports[] = [
                    'date' => $value,
                    'multiply' => $multiply
                ];
            }

            $user->setAttribute('reports', $reports);
        }
        return view('livewire.report.show', [
            'attendances' => Attendance::with('user.profile')->whereProjectId($this->project->id)->paginate(10, pageName: 'attendance-page'),
            'leaves' => Leave::with('user.profile')->whereProjectId($this->project->id)->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->paginate(10, pageName: 'leave-page'),
            'projects' => [],
            'users' => $users,
            'listDates' => $listDates,
        ]);
    }
}
