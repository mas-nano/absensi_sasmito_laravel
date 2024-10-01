<?php

declare(strict_types=1);

namespace App\Livewire\Project;

use App\Models\OvertimeLimit;
use DB;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Toaster;

class TableOvertime extends Component
{
    #[Locked]
    public int $project_id;

    #[Reactive]
    public $overtimeLimit;

    public function mount($overtimeLimit, $project_id): void
    {
        $this->project_id = $project_id;
        $this->overtimeLimit = $overtimeLimit;
    }

    public function render(): View
    {
        return view('livewire.project.table-overtime', [
            'daysText' => ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        ]);
    }

    public function removeTimeLimit($id)
    {
        DB::beginTransaction();

        try {
            OvertimeLimit::where('id', $id)->delete();
            $this->dispatch('$refresh')->to(Edit::class);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            Toaster::error('Gagal hapus. Silakan coba beberapa saat lagi');
            //throw $th;
        }
    }
}
