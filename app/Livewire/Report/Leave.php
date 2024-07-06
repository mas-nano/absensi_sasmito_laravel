<?php

namespace App\Livewire\Report;

use App\Models\Project;
use Carbon\Carbon;
use Livewire\Component;

class Leave extends Component
{
    public ?Project $project;

    public $dates = [];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        $leaves = $this->project->leaves()
            ->when(count($this->dates) == 2, function ($query) {
                $query->whereBetween('start_date', [
                    Carbon::parse($this->dates[0])->setTimezone('Asia/Jakarta')->toDateString(),
                    Carbon::parse($this->dates[1])->setTimezone('Asia/Jakarta')->toDateString()
                ]);
            })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10, pageName: 'leave-page');
        $this->project->setRelation('leaves', $leaves);
        return view('livewire.report.leave');
    }
}
