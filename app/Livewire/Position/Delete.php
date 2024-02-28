<?php

namespace App\Livewire\Position;

use App\Models\Position;
use LivewireUI\Modal\ModalComponent;
use Toaster;

class Delete extends ModalComponent
{
    public $position;

    public function mount($position)
    {

        $this->position = Position::find($position);
    }

    public function render()
    {
        return view('livewire.position.delete');
    }

    public function  save()
    {
        try {
            $this->position->delete();
            Toaster::success('Jabatan berhasil dihapus');
            $this->dispatch('refresh-list-position');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('Gagal hapus. Silakan coba beberapa saat lagi');
        }
    }
}
