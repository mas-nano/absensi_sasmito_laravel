<?php

namespace App\Livewire\Role;

use App\Models\Role;
use Livewire\Attributes\On;
use Livewire\Component;

class Edit extends Component
{
    public ?Role $role;

    public $search;

    public function mount(Role $role)
    {
        $role->load('users');
        $this->role = $role;
    }

    public function render()
    {
        return view('livewire.role.edit', [
            'role' => $this->role
        ]);
    }

    #[On('role-employee-refresh')]
    public function refresh()
    {
    }
}
