<?php

namespace App\Livewire\Position;

use App\Models\Position;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;
use Toaster;

class Edit extends ModalComponent
{
    public ?Position $position;

    public $name;

    public function mount(Position $position)
    {
        $this->position = $position;

        $this->name = $position->name;
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('positions')->ignore($this->position->id)]
        ];
    }

    public function validationAttributes()
    {
        return [
            'name' => 'Nama Jabatan'
        ];
    }

    public function render()
    {
        return view('livewire.position.edit');
    }

    public function save()
    {
        $this->validate();

        try {
            $this->position->name = $this->name;
            $this->position->save();

            Toaster::success('Jabatan berhasil diubah');
            $this->dispatch('refresh-list-position');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('Gagal simpan. Coba beberapa saat lagi');
        }
    }
}
