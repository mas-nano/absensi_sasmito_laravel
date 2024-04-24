<?php

namespace App\Livewire\Leave;

use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;

class Detail extends Component
{
    public $project;

    #[On('refresh-list-leave')]
    public function refresh()
    {
    }

    public function mount(Project $project)
    {
        $this->project = $project;
    }
    public function render()
    {
        $this->project->load(['leaves' => function ($query) {
            $query->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->latest();
        }, 'leaves.user']);
        return view('livewire.leave.detail', [
            'leaves' => $this->project->leaves
        ]);
    }
}
