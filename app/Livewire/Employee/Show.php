<?php

namespace App\Livewire\Employee;

use App\Models\Profile;
use Livewire\Component;

class Show extends Component
{
    public Profile $profile;

    public function mount(Profile $profile): void
    {
        $profile->load('user');
        $this->profile = $profile;
    }

    public function render()
    {
        return view('livewire.employee.show', [
            'profile' => $this->profile
        ]);
    }
}
