<?php

namespace App\Livewire\Employee;

use App\Models\User;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;
use Storage;

class DeleteModal extends ModalComponent
{
    public $id;

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        return view('livewire.employee.delete-modal');
    }

    public function save()
    {
        $user = User::with('profile')->find($this->id);
        if (!$user) {
            Toaster::info('Karyawan tidak ditemukan');
            return;
        }

        try {
            if ($user->profile->profile_picture !== null && Storage::exists($user->profile->profile_picture)) {
                Storage::delete($user->profile->profile_picture);
            }
            $user->delete();
            Toaster::success('Karyawan berhasil dihapus');
            $this->closeModal();
            $this->dispatch('refresh-list-employee');
        } catch (\Throwable $th) {
            Toaster::error('Gagal Hapus. Silakan coba beberapa saat lagi');
        }
    }
}
