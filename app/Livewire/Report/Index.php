<?php

namespace App\Livewire\Report;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;

    public function render()
    {
        if (auth()->user()->role_id == 1) {
            $projects = Project::where('name', 'ilike', '%' . $this->search . '%')->paginate(10);
        } else {
            $projects = Project::where('id', auth()->user()->project_id)->paginate(10);
        }
        return view('livewire.report.index', [
            'projects' => $projects
        ]);
    }
}
