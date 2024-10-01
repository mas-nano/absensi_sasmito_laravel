<?php

namespace App\Livewire\Project;

use App\Models\MinusMultiply as ModelsMinusMultiply;
use App\Models\Project;
use DB;
use Livewire\Component;
use Toaster;

class MinusMultiply extends Component
{
    protected $listeners = [
        '$refresh'
    ];

    public int $project_id;

    public function render()
    {
        $project = Project::with('minusMultiplies')->find($this->project_id);
        return view('livewire.project.minus-multiply', [
            'project' => $project
        ]);
    }

    public function destroy(int $id)
    {
        DB::beginTransaction();
        try {
            ModelsMinusMultiply::where('id', $id)->delete();
            DB::commit();
            $this->dispatch('$refresh');
        } catch (\Throwable $th) {
            DB::rollBack();
            Toaster::error('Gagal hapus. Silakan coba beberapa saat lagi');
        }
    }
}
