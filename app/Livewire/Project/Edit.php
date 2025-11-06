<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Traits\UploadFile;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Storage;
use Toaster;

class Edit extends Component
{
    use WithFileUploads, UploadFile;
    protected $listeners = [
        '$refresh'
    ];

    public ?Project $project;

    #[Validate('nullable|image|max:4096', as: 'Foto Proyek')]
    public $photo = null;

    #[Validate('required')]
    public $lat;

    #[Validate('required')]
    public $lng;

    #[Validate('required', as: 'Nama Proyek')]
    public $name;

    #[Validate('required', as: 'Alamat')]
    public $address;

    #[Validate('nullable', as: 'Jam Masuk')]
    public $check_in_time;

    #[Validate('nullable', as: 'Jam Keluar')]
    public $check_out_time;

    #[Validate('nullable', as: 'Jam Mulai Toleransi Telat Setelah Lembur')]
    public $start_tolerance_overtime;

    #[Validate('nullable', as: 'Durasi Toleransi Telat Setelah Lembur')]
    public $duration_tolerance_overtime;

    public $currentPhoto = null;

    public function mount(Project $project)
    {
        $this->project = $project->load('users.profile', 'users.role', 'minusMultiplies');
        $this->currentPhoto = $project->photo;
        $this->lat = $project->lat;
        $this->lng = $project->lng;
        $this->name = $project->name;
        $this->address = $project->address;
        $this->check_in_time = $project->check_in_time;
        $this->check_out_time = $project->check_out_time;
        $this->start_tolerance_overtime = $project->start_tolerance_overtime;
        $this->duration_tolerance_overtime = $project->duration_tolerance_overtime;
    }

    public function render()
    {
        return view('livewire.project.edit', [
            'project' => $this->project
        ]);
    }

    public function save()
    {
        $this->validate();

        if ($this->photo != null) {
            if ($this->project->photo != null && Storage::exists($this->project->photo)) {
                Storage::delete($this->project->photo);
            }
            $this->project->photo = $this->upload('project_photo', $this->photo);
        }
        $this->project->name = $this->name;
        $this->project->address = $this->address;
        $this->project->lat = $this->lat;
        $this->project->lng = $this->lng;
        $this->project->check_in_time = $this->check_in_time;
        $this->project->check_out_time = $this->check_out_time;
        $this->project->start_tolerance_overtime = $this->start_tolerance_overtime;
        $this->project->duration_tolerance_overtime = filled($this->duration_tolerance_overtime) ? $this->duration_tolerance_overtime : null;
        $this->project->save();

        Toaster::success('Proyek berhasil diubah');
        return $this->redirectRoute('project.index', navigate: true);
    }
}
