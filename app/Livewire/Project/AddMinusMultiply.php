<?php

namespace App\Livewire\Project;

use App\Livewire\Project\MinusMultiply as ProjectMinusMultiply;
use App\Models\MinusMultiply;
use App\Models\OvertimeLimit;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use LivewireUI\Modal\ModalComponent;
use Toaster;

class AddMinusMultiply extends ModalComponent
{
    #[Locked]
    public $project_id;

    #[Validate('required', 'date_format:H:i')]
    public $minus_time_limit;

    #[Validate('required', 'numeric')]
    public $minus = 1;

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
        return view('livewire.project.add-minus-multiply', [
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
            MinusMultiply::create([
                'project_id' => $this->project_id,
                'minus_time_limit' => $this->minus_time_limit,
                'minus' => $this->minus,
                'days' => json_encode($days),
            ]);
            DB::commit();
            $this->dispatch('$refresh')->to(ProjectMinusMultiply::class);
            $this->closeModal();
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th);
            Toaster::error('Gagal menyimpan. Coba beberapa saat lagi');
        }
    }
}
