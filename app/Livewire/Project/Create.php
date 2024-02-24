<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Traits\UploadFile;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class Create extends Component
{
    use WithFileUploads;
    use UploadFile;

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

    public function render()
    {
        return view('livewire.project.create');
    }

    public function save()
    {
        $this->validate();

        $project = new Project();
        if ($this->photo != null) {
            $project->photo = $this->upload('project_photo', $this->photo);
        }
        $project->name = $this->name;
        $project->address = $this->address;
        $project->lat = $this->lat;
        $project->lng = $this->lng;
        $project->check_in_time = $this->check_in_time;
        $project->check_out_time = $this->check_out_time;
        $project->save();

        Toaster::success('Proyek berhasil dibuat');
        return $this->redirectRoute('project.edit', ['project' => $project->uuid], navigate: true);
    }
}
