<?php

namespace App\Livewire\Role;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;

class AddEmployeeModal extends ModalComponent
{
    public ?Role $role;

    #[Validate('required|exists:users,id')]
    public $user_id;

    public function mount($uuid)
    {
        $role = Role::whereUuid($uuid)->first();
        if (!$role) {
            Toaster::error('Role tidak ada');
            $this->closeModal();
        }
        $this->role = $role;
    }

    public function render()
    {
        return view('livewire.role.add-employee-modal', [
            'profiles' => Profile::with('user')->whereHas('user', function ($query) {
                $query->where('role_id', '!=', $this->role->id)->orWhere('role_id', null);
            })->get()
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            $user = User::find($this->user_id);
            $user->role_id = $this->role->id;
            $user->save();

            Toaster::success('User berhasil ditambahkan');
            $this->dispatch('role-employee-refresh');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('User gagal ditambahkan. Silakan coba beberapa saat lagi');
            $this->closeModal();
        }
    }
}
