<?php

namespace App\Livewire\Report;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Project;
use Livewire\Component;

class Show extends Component
{
    public ?Project $project;

    public $dates;

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.report.show', [
            'attendances' => Attendance::with('user.profile')->whereProjectId($this->project->id)->paginate(10, pageName: 'attendance-page'),
            'leaves' => Leave::with('user.profile')->whereProjectId(null)->paginate(10, pageName: 'leave-page'),
            'projects' => []
        ]);
    }
}
