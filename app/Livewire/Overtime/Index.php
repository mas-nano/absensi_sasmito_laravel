<?php

namespace App\Livewire\Overtime;

use App\Models\Overtime;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = [
        '$refresh'
    ];

    public $search;
    public function render()
    {
        return view('livewire.overtime.index', [
            'overtimes' => Overtime::query()
                ->with(['user' => ['profile']])
                ->when($this->search, function ($query) {
                    return $query->whereHas('user', function ($query) {
                        $query->where('name', 'ilike', '%' . $this->search . '%');
                    })
                        ->orWhereHas('project', function ($query) {
                            $query->where('name', 'ilike', '%' . $this->search . '%');
                        });
                })
                ->latest()
                ->paginate(10)
        ]);
    }
}
