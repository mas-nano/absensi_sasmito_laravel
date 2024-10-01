<?php

namespace App\Livewire\Report;

use App\Models\Project;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $search;

    public function updated($attribute)
    {
        if ($attribute == 'search') {
            $this->resetPage();
        }
    }

    public function render()
    {
        $projects = Project::query()
            ->where('name', 'ilike', '%' . $this->search . '%')
            ->when(Auth::user()->hasPermission('view-own-project'), function ($query) {
                return $query->where('id', Auth::user()->project_id);
            })
            ->when(
                !Auth::user()->hasPermission('view-own-project') &&
                    Auth::user()->hasPermission('view-other-project'),
                function ($query) {
                    $projectArr = [];
                    $projectArr[] = Auth::user()->project_id;
                    array_push($projectArr, ...Auth::user()->projects->pluck('id')->toArray());
                    $query->whereIn('id', $projectArr);
                }
            )->paginate(10);
        // if (auth()->user()->role_id == 1) {
        //     $projects = Project::where('name', 'ilike', '%' . $this->search . '%')->paginate(10);
        // } else {
        //     $projects = Project::where('id', auth()->user()->project_id)->paginate(10);
        // }
        return view('livewire.report.index', [
            'projects' => $projects
        ]);
    }
}
