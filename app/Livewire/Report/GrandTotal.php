<?php

namespace App\Livewire\Report;

use Livewire\Attributes\On;
use Livewire\Component;

class GrandTotal extends Component
{
    public $total;

    public $listDates;

    public function mount($users, $listDates)
    {
        $this->listDates = $listDates;
        foreach ($users as $value) {
            $this->total[$value->id] = 0;
        }
    }

    public function render()
    {
        return view('livewire.report.grand-total', [
            'grandTotal' => number_format(array_sum($this->total), 0, thousands_separator: '.')
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
