<?php

namespace App\Livewire\Report;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Component;

class Show extends Component
{
    public ?Project $project;

    public $dates;

    public function mount(Project $project)
    {
        $project->load('users.role');
        $this->project = $project;
        $this->dates = [
            date('Y-m-01'), date('Y-m-t')
        ];
    }

    public function updating($property, $value)
    {
        if ($property == 'dates') {
            if (is_array($value)) {
                $this->dates = array_map(function ($val) {
                    return Carbon::parse($val)->setTimezone('Asia/Jakarta')->toDateString();
                }, $value);
            }
        }
    }

    public function render()
    {
        $users = User::with(['profile', 'attendances' => function ($query) {
            $query->where('project_id', $this->project->id)->where('date', '>=', $this->dates ? $this->dates[0] : Carbon::now()->format('Y-m-01'))->where('date', '<=',  $this->dates ? $this->dates[1] : Carbon::now()->format('Y-m-t'));
        }, 'leaves' => function ($query) {
            $query->where('project_id', $this->project->id)->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya']);
        }])->where('project_id', $this->project->id)->where('role_id', '!=', 2)->get();

        $listDates = [];
        if ($this->dates) {
            $listDates = array_map(function ($val) {
                return $val->setTimezone('Asia/Jakarta')->toDateString();
            }, CarbonPeriod::create($this->dates[0], $this->dates[1])->toArray());
        } else {
            for ($i = 1; $i <= date('t'); $i++) {
                array_push($listDates, date('Y-m-' . str_pad($i, 2, "0", STR_PAD_LEFT)));
            }
        }
        return view('livewire.report.show', [
            'attendances' => Attendance::with('user.profile')->whereProjectId($this->project->id)->paginate(10, pageName: 'attendance-page'),
            'leaves' => Leave::with('user.profile')->whereProjectId(null)->paginate(10, pageName: 'leave-page'),
            'projects' => [],
            'users' => $users,
            'listDates' => $listDates,
        ]);
    }
}
