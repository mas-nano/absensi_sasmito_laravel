<?php

namespace App\Livewire\Project;

use App\Models\OvertimeLimit;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Toaster;

class AddOvertime extends ModalComponent
{
    #[Locked]
    public $project_id;

    #[Validate('required', 'date_format:H:i')]
    public $check_out_time_limit;

    #[Validate('required', 'numeric')]
    public $multiply = 1;

    public function mount($project_id)
    {
        $this->project_id = $project_id;
    }

    public function render()
    {
        return view('livewire.project.add-overtime');
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            OvertimeLimit::create([
                'project_id' => $this->project_id,
                'check_out_time_limit' => $this->check_out_time_limit,
                'multiply' => $this->multiply
            ]);
            DB::commit();
            $this->dispatch('$refresh')->to(Edit::class);
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollBack();
            Toaster::error('Gagal menyimpan. Coba beberapa saat lagi');
        }
    }
}
