<?php

namespace App\Livewire\Report;

use App\Models\Project;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Attendance extends Component
{
    #[Locked]
    public ?Project $project;

    public $dates;

    /**
     * Mounts the given Project object to the current instance.
     *
     * @param Project $project The Project object to be mounted.
     * @return void
     */
    public function mount(Project $project)
    {
        $this->project = $project;
        $this->dates = [
            date('Y-m-01'),
            date('Y-m-t')
        ];
    }

    /**
     * Renders the attendance report for the given project.
     *
     * @return \Illuminate\Contracts\View\View The rendered attendance report view.
     */
    public function render()
    {
        $this->project->load(['users' => function ($query) {
            $query->with(['attendances' => function ($query) {
                $query->where('date', '>=', Carbon::parse($this->dates[0])->setTimezone('Asia/Jakarta')->toDateString())
                    ->where('date', '<=', Carbon::parse($this->dates[1])->setTimezone('Asia/Jakarta')->toDateString())
                    ->where('project_id', $this->project->id);
            }, 'leaves' => function ($query) {
                $query->where('project_id', $this->project->id)->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('status', 2);
            }])
                ->where('role_id', '!=', 2);
        }]);

        $listDates = array_map(
            fn($date) => $date->setTimezone('Asia/Jakarta')->toDateString(),
            CarbonPeriod::create(
                $this->dates[0],
                $this->dates[1]
            )->toArray()
        );

        foreach ($this->project->users as $user) {
            $reports = [];
            foreach ($listDates as $date) {
                $attend = 0;
                $attendanceIn = $user->attendances->where('date', $date)->where('type', 'in')->sortBy('id')->first();
                $attendanceOut = $user->attendances->where('date', $date)->where('type', 'out')->sortBy('id')->first();
                if ($attendanceIn || $attendanceOut) {
                    if (!$user->leaves->whereIn('type', ['Dinas Luar', 'Sakit', 'Lainnya'])->where('start_date', '<=', $date)->where('to_date', '>=', $date)->first()) {
                        $attend = 1;
                    }
                }

                if ($user->leaves->where('type', 'Dinas Luar')->where('start_date', '<=', $date)->where('to_date', '>=', $date)->first()) {
                    $attend = 1;
                }

                $reports[] = [
                    'date' => $date,
                    'attend' => $attend
                ];
            }

            $user->setAttribute('reports', $reports);
        }

        return view('livewire.report.attendance', [
            'listDates' => $listDates
        ]);
    }
}
