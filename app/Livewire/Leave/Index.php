<?php

namespace App\Livewire\Leave;

use App\Models\Project;
use Livewire\Attributes\On;
use Livewire\Component;
use Symfony\Component\Console\Output\ConsoleOutput;

class Index extends Component
{
    public $search;


    public function render()
    {
        $rolePermitted = collect([1, 2]);
        if ($rolePermitted->contains(auth()->user()->role_id)) {
            $projects = Project::where('name', 'ilike', '%' . $this->search . '%')->paginate(10);
        } else {
            $projects = Project::where('id', auth()->user()->project_id)->paginate(10);
        }
        return view('livewire.leave.index', [
            'projects' => $projects
        ]);
    }
}
