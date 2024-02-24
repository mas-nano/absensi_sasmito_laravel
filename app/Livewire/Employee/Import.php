<?php

namespace App\Livewire\Employee;

use App\Imports\ImportProfile;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;
use Maatwebsite\Excel\Facades\Excel;
use Masmerise\Toaster\Toaster;

class Import extends ModalComponent
{
    use WithFileUploads;

    #[Validate('required|csv')]
    public $file;

    public function render()
    {
        return view('livewire.employee.import');
    }

    public function save()
    {
        $this->validate();

        try {
            Excel::import(new ImportProfile, $this->file);
            Toaster::success('Import pegawai berhasil');
            $this->dispatch('refresh-list-employee');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('Import pegawai gagal. Silakan coba beberapa saat lagi' . $th->getMessage());
        }
    }
}
