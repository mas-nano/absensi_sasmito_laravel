<?php

namespace App\Livewire\Report;

use App\Models\Overtime;
use App\Models\OvertimeLimit;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class GrandTotal extends Component
{
    public $total;

    public $listDates;

    public function mount($users, $listDates)
    {
        $this->listDates = $listDates;
        foreach ($users as $user) {
            $user->load('profile', 'leaves', 'attendances');
            $uang_makan = $user->profile->lunch_price;
            $total_uang_makan = 0;
            $overtimeLimit = OvertimeLimit::query()
                ->where('project_id', $user->project_id)
                ->orderBy('multiply', 'asc')
                ->get();
            $timeLimit = Setting::query()
                ->where('field', 'time')
                ->first()
                ->value ??
                '00:00';
            foreach ($this->listDates as $date) {
                if ($user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->where('type', 'Dinas Luar')->first()) {
                    // $attend++;
                    //apakah dapat uang makan ?
                } elseif (
                    $user->attendances->contains('date', $date) &&
                    !$user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->whereIn('type', ['Sakit', 'Lainnya'])->first()
                ) {
                    $attendanceOut = $user->attendances->where('date', $date)->where('type', 'out')->sortBy('id')->first();
                    $attendanceIn = $user->attendances->where('date', $date)->where('type', 'in')->sortBy('id')->first();
                    if ($attendanceOut && $attendanceIn) {
                        $overtimeExist = Overtime::query()
                            ->where('user_id', $user->id)
                            ->where('project_id', $user->project_id)
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

            $this->total += $total_uang_makan;
        }
    }

    public function render()
    {
        return view('livewire.report.grand-total', [
            'grandTotal' => number_format($this->total, 0, thousands_separator: '.')
        ]);
    }

    #[On('update-total')]
    public function totalUpdate($id, $value)
    {
        $totalTmp = $this->total;
        $totalTmp[$id] = $value;
        $this->total = $totalTmp;
    }
}
