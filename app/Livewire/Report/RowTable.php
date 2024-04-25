<?php

namespace App\Livewire\Report;

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
        foreach ($listDates as $value) {
            if ($this->user->leaves->where('start_date', '<=', $value)->where('to_date', '>=', $value)->where('type', 'Dinas Luar')->first()) {
                $this->attend++;
            } elseif ($this->user->attendances->contains('date', $value) && !$this->user->leaves->where('start_date', '<=', $value)->where('to_date', '>=', $value)->whereIn('type', ['Sakit', 'Lainnya'])->first()) {
                $this->attend++;
            }
        }
    }

    public function updating($property, $value): void
    {
        if ($property == 'uang_makan') {
            $this->attend = 0;
            $uang_makan = join("", explode(".", join("", explode(",", join("", explode(' ', $value))))));
            foreach ($this->listDates as $date) {
                if ($this->user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->where('type', 'Dinas Luar')->first()) {
                    $this->attend++;
                } elseif ($this->user->attendances->contains('date', $date) && !$this->user->leaves->where('start_date', '<=', $date)->where('to_date', '>=', $date)->whereIn('type', ['Sakit', 'Lainnya'])->first()) {
                    $this->attend++;
                }
            }
            if (is_numeric($uang_makan)) {
                $console = new ConsoleOutput();
                $console->writeln('info');
                $this->total_uang_makan = number_format($uang_makan * $this->attend, 0, ",", ".");
                $this->dispatch('update-total', id: $this->user->id, value: $uang_makan * $this->attend);
            }
        }
    }

    public function render()
    {
        return view('livewire.report.row-table');
    }
}
