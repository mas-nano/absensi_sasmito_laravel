<?php

namespace App\Livewire\Position;

use App\Models\Position;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;

class Create extends ModalComponent
{
    #[Validate('required|unique:positions')]
    public $name;

    public function render()
    {
        return view('livewire.position.create');
    }

    public function save()
    {
        $this->validate();

        try {
            $position = new Position();
            $position->name = $this->name;
            $position->save();

            Toaster::success('Jabatan berhasil disimpan');
            $this->dispatch('refresh-list-position');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('Gagal simpan. Coba beberapa saat lagi');
        }
    }
}
