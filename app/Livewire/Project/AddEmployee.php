<?php

namespace App\Livewire\Project;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;

class AddEmployee extends ModalComponent
{
    public $project_id;

    #[Validate('required', as: 'Pegawai')]
    public $user_id;

    #[Validate('required', as: 'Jenis Pegawai')]
    public $role_id;

    public function mount($project_id)
    {
        $this->project_id = $project_id;
    }

    public function render()
    {
        $profiles = Profile::with('user')->get();
        $roles = Role::all();
        return view('livewire.project.add-employee', [
            'profiles' => $profiles,
            'roles' => $roles
        ]);
    }

    public function save()
    {
        $this->validate();

        $user = User::find($this->user_id);
        if (!$user) {
            Toaster::info('User tidak ditemukan');
            return;
        }

        $user->project_id = $this->project_id;
        $user->role_id = $this->role_id;
        $user->save();
        $this->closeModal();
        $this->dispatch('refresh-list-employee-project');
    }
}
