<?php

namespace App\Livewire\Project;

use App\Models\OvertimeLimit;
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

    public $days = [
        0 => true,
        1 => true,
        2 => true,
        3 => true,
        4 => true,
        5 => true,
        6 => true,
    ];

    public function mount($project_id)
    {
        $this->project_id = $project_id;
    }

    public function render()
    {
        return view('livewire.project.add-overtime', [
            'daysText' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        ]);
    }

    public function save()
    {
        $this->validate();
        
        DB::beginTransaction();
        try {
            $days = [];
            foreach ($this->days as $key => $day) {
                if ($day) {
                    $days[] = $key;
                }
            }
            OvertimeLimit::create([
                'project_id' => $this->project_id,
                'check_out_time_limit' => $this->check_out_time_limit,
                'multiply' => $this->multiply,
                'days' => json_encode($days),
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
