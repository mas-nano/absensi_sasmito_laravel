<?php

namespace App\Livewire\Overtime;

use App\Models\Overtime;
use DB;
use Livewire\Attributes\Locked;
use LivewireUI\Modal\ModalComponent;
use Toaster;

class Delete extends ModalComponent
{
    #[Locked]
    public $overtime;

    public function mount($overtime_id)
    {
        $this->overtime = Overtime::find($overtime_id);
    }

    public function render()
    {
        return view('livewire.overtime.delete');
    }

    public function save()
    {
        if (!$this->overtime) {
            Toaster::error('Lembur tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            $this->overtime->delete();
            DB::commit();
            Toaster::success('Lembur berhasil dihapus');
            $this->dispatch('$refresh')->to(Index::class);
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollBack();
            Toaster::error('Gagal hapus. Silakan coba beberapa saat lagi');
        }
    }
}
