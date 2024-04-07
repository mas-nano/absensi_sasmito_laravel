<?php

namespace App\Livewire\Report;

use Livewire\Component;

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
            if ($this->user->leaves->where('start_date', '<=', $value)->where('to_date', '>=', $value)->where('type', 'Dinas Luar')->first() || $this->user->attendances->contains('date', $value)) {
                $this->attend++;
            }
        }
    }

    public function updating($property, $value): void
    {
        if ($property == 'uang_makan') {
            $this->attend = 0;
            $uang_makan = join("", explode(".", join("", explode(",", join("", explode(' ', $value))))));
            foreach ($this->listDates as $value) {
                if ($this->user->leaves->where('start_date', '<=', $value)->where('to_date', '>=', $value)->where('type', 'Dinas Luar')->first() || $this->user->attendances->contains('date', $value)) {
                    $this->attend++;
                }
            }
            $this->total_uang_makan = number_format($uang_makan * $this->attend, 0, ",", ".");
        }
    }

    public function render()
    {
        return view('livewire.report.row-table');
    }
}
