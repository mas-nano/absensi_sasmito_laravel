<?php

namespace App\Livewire\Report;

use App\Models\Project;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use LivewireUI\Modal\ModalComponent;

class PrintReport extends ModalComponent
{
    public ?Project $project;

    public $dates;
    public $user_id = '';

    public function mount($uuid)
    {
        $this->project = Project::whereUuid($uuid)->first();
    }

    public function render()
    {
        return view('livewire.report.print-report', [
            'users' => User::with('profile')->whereProjectId($this->project->id)->get()
        ]);
    }

    public function save()
    {
        return response()->streamDownload(function () {
            $pdf = Pdf::loadView('livewire.report.all-employee-report');
            echo $pdf->stream();
        }, 'report.pdf');
    }
}
