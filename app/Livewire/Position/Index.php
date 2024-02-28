<?php

namespace App\Livewire\Position;

use App\Models\Position;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('refresh-list-position')]
    public function refresh()
    {
    }

    public function render()
    {
        return view('livewire.position.index', [
            'positions' => Position::all()
        ]);
    }
}
