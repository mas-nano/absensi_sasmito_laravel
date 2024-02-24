<?php

namespace App\Livewire\Role;

use App\Models\Role;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.role.index', [
            'roles' => Role::all()
        ]);
    }
}
