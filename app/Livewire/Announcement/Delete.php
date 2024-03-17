<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement;
use LivewireUI\Modal\ModalComponent;
use Storage;
use Toaster;

class Delete extends ModalComponent
{
    public $id;
    public function mount($id)
    {
        $this->id = $id;
    }
    public function render()
    {
        return view('livewire.announcement.delete');
    }

    public function save()
    {
        try {
            $announcement = Announcement::findOrFail($this->id);
            if ($announcement->attachment && Storage::exists($announcement->attachment)) {
                Storage::delete($announcement->attachment);
            }
            $announcement->delete();
            Toaster::success('Informasi berhasi dihapus');
            $this->dispatch('announcement-refresh');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('Gagal hapus. Coba beberapa saat lagi');
        }
    }
}
