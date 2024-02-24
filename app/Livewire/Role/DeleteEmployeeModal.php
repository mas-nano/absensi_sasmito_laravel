<?php

namespace App\Livewire\Role;

use App\Models\User;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;

class DeleteEmployeeModal extends ModalComponent
{
    public ?User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.role.delete-employee-modal');
    }

    public function save()
    {
        try {
            $this->user->role_id = null;
            $this->user->save();

            Toaster::success('Role User berhasil dihapus');
            $this->dispatch('role-employee-refresh');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('Role User gagal dihapus. Silakan coba beberapa saat lagi');
            $this->closeModal();
        }
    }
}
