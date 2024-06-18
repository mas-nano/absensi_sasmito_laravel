<?php

namespace App\Livewire\Report;

use App\Models\Overtime;
use App\Models\OvertimeLimit;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;
use Symfony\Component\Console\Output\ConsoleOutput;

class RowTable extends Component
{
    public $user;
    public $uang_makan;
    public $total_uang_makan;
    public $listDates;
    public $attend;

    public function mount($user, $listDates)
    {
        $this->attend = 0;
        $this->user = $user;
        $this->listDates = $listDates;
        $this->total_uang_makan = 0;
        // foreach ($listDates as $value) {
        //     if ($this->user->leaves->where('start_date', '<=', $value)->where('to_date', '>=', $value)->where('type', 'Dinas Luar')->first()) {
        //         $this->attend++;
        //     } elseif ($this->user->attendances->contains('date', $value) && !$this->user->leaves->where('start_date', '<=', $value)->where('to_date', '>=', $value)->whereIn('type', ['Sakit', 'Lainnya'])->first()) {
        //         $this->attend++;
        //     }
        // }
    }

    public function updating($property, $value): void
    {
        if ($property == 'uang_makan') {
            if ($value == '') $value = 0;
            $console = new ConsoleOutput();
            $this->attend = 0;
            // $uang_makan = join("", explode(".", join("", explode(",", join("", explode(' ', $value))))));
            $uang_makan = $this->user->profile->lunch_price;
            $total_uang_makan = 0;
            $overtimeLimit = OvertimeLimit::where('project_id', $this->user->project_id)->orderBy('multiply', 'asc')->get();
            $timeLimit = Setting::where('field', 'time')->first()->value ?? '00:00';
            // dd($overtimeLimit);
            foreach ($this->listDates as $date) {
                if ($this->user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->where('type', 'Dinas Luar')->first()) {
                    $this->attend++;
                } elseif ($this->user->attendances->contains('date', $date) && !$this->user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->whereIn('type', ['Sakit', 'Lainnya'])->first()) {
                    $attendanceOut = $this->user->attendances->where('date', $date)->where('type', 'out')->sortBy('id')->first();
                    $attendanceIn = $this->user->attendances->where('date', $date)->where('type', 'in')->sortBy('id')->first();
                    $this->attend++;
                    if ($attendanceOut && $attendanceIn) {
                        foreach ($overtimeLimit as $key => $overtime) {
                            $console->writeln('cek ' . (Carbon::parse($attendanceOut->created_at) >= Carbon::parse($overtime->check_out_time_limit) ? 'true' : 'false'));

                            if ($timeLimit > '00:00') {
                                if (
                                    Carbon::parse($overtime->check_out_time_limit) <=
                                    Carbon::parse($timeLimit) &&
                                    Carbon::parse($overtime->check_out_time_limit) >=
                                    Carbon::parse('00:00')
                                ) {
                                    if (
                                        Carbon::parse($attendanceOut->created_at) <=
                                        Carbon::parse($date . ' ' . $overtime->check_out_time_limit)->addDay() ||
                                        $overtimeLimit->count() ==
                                        $key + 1
                                    ) {
                                        $console->writeln('tengah-tengah ' . $overtime->multiply . ' ' . $overtime->id);
                                        $total_uang_makan += $uang_makan * $overtime->multiply;
                                        break;
                                    }
                                } else {
                                    if (
                                        Carbon::parse($attendanceOut->created_at) <=
                                        Carbon::parse($date . ' ' . $overtime->check_out_time_limit) ||
                                        $overtimeLimit->count() ==
                                        $key + 1
                                    ) {
                                        $console->writeln('ga di tengah ' . $overtime->multiply . ' ' . $overtime->id);
                                        $total_uang_makan += $uang_makan * $overtime->multiply;
                                        break;
                                    }
                                }
                            } else {
                                if (
                                    Carbon::parse($attendanceOut->created_at) <=
                                    Carbon::parse($date . ' ' . $overtime->check_out_time_limit) ||
                                    $overtimeLimit->count() ==
                                    $key + 1
                                ) {
                                    $console->writeln('kur den timelimit ' . $overtime->multiply . ' ' . $overtime->id);
                                    $total_uang_makan += $uang_makan * $overtime->multiply;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
            // if (is_numeric($uang_makan)) {
            $this->total_uang_makan = number_format($total_uang_makan, 0, ",", ".");
            $this->dispatch('update-total', id: $this->user->id, value: $total_uang_makan);
            // }
        }
    }

    public function render()
    {
        $uang_makan = $this->user->profile->lunch_price;
        $total_uang_makan = 0;
        $overtimeLimit = OvertimeLimit::query()
            ->where('project_id', $this->user->project_id)
            ->orderBy('multiply', 'asc')
            ->get();
        $timeLimit = Setting::query()
            ->where('field', 'time')
            ->first()
            ->value ??
            '00:00';
        foreach ($this->listDates as $date) {
            if ($this->user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->where('type', 'Dinas Luar')->first()) {
                $this->attend++;
            } elseif (
                $this->user->attendances->contains('date', $date) &&
                !$this->user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->whereIn('type', ['Sakit', 'Lainnya'])->first()
            ) {
                $attendanceOut = $this->user->attendances->where('date', $date)->where('type', 'out')->sortBy('id')->first();
                $attendanceIn = $this->user->attendances->where('date', $date)->where('type', 'in')->sortBy('id')->first();
                $this->attend++;
                if ($attendanceOut && $attendanceIn) {
                    $overtimeExist = Overtime::query()
                        ->where('user_id', $this->user->id)
                        ->where('project_id', $this->user->project_id)
                        ->where('date', $date)
                        ->first();

                    if (!$overtimeExist) {
                        $total_uang_makan += $uang_makan * $overtimeLimit[0]->multiply;
                    } else {
                        foreach ($overtimeLimit as $key => $overtime) {
                            // $console->writeln('cek ' . (Carbon::parse($attendanceOut->created_at) >= Carbon::parse($overtime->check_out_time_limit) ? 'true' : 'false'));

                            if ($timeLimit > '00:00') {
                                if (
                                    Carbon::parse($overtime->check_out_time_limit) <=
                                    Carbon::parse($timeLimit) &&
                                    Carbon::parse($overtime->check_out_time_limit) >=
                                    Carbon::parse('00:00')
                                ) {
                                    if (
                                        Carbon::parse($attendanceOut->created_at) <=
                                        Carbon::parse($date . ' ' . $overtime->check_out_time_limit)->addDay() ||
                                        $overtimeLimit->count() ==
                                        $key + 1
                                    ) {
                                        // $console->writeln('tengah-tengah ' . $overtime->multiply . ' ' . $overtime->id);
                                        $total_uang_makan += $uang_makan * $overtime->multiply;
                                        break;
                                    }
                                } else {
                                    if (
                                        Carbon::parse($attendanceOut->created_at) <=
                                        Carbon::parse($date . ' ' . $overtime->check_out_time_limit) ||
                                        $overtimeLimit->count() ==
                                        $key + 1
                                    ) {
                                        // $console->writeln('ga di tengah ' . $overtime->multiply . ' ' . $overtime->id);
                                        $total_uang_makan += $uang_makan * $overtime->multiply;
                                        break;
                                    }
                                }
                            } else {
                                if (
                                    Carbon::parse($attendanceOut->created_at) <=
                                    Carbon::parse($date . ' ' . $overtime->check_out_time_limit) ||
                                    $overtimeLimit->count() ==
                                    $key + 1
                                ) {
                                    // $console->writeln('kur den timelimit ' . $overtime->multiply . ' ' . $overtime->id);
                                    $total_uang_makan += $uang_makan * $overtime->multiply;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->total_uang_makan = number_format($total_uang_makan, 0, ",", ".");

        return view('livewire.report.row-table');
    }
}
