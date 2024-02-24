<?php

namespace App\Livewire\Project;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    public $search;

    #[On('refresh-list-project')]
    public function refresh()
    {
    }

    public function render()
    {
        $projects = Project::where('name', 'ilike', '%' . $this->search . '%')->paginate(10);
        return view('livewire.project.index', [
            'projects' => $projects
        ]);
    }
}
