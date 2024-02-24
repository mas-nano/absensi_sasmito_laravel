<?php

namespace App\Livewire\Project;

use App\Models\Project;
use LivewireUI\Modal\ModalComponent;
use Masmerise\Toaster\Toaster;
use Storage;

class DeleteModal extends ModalComponent
{
    public $uuid;

    public function mount($uuid)
    {
        $this->uuid = $uuid;
    }

    public function render()
    {
        return view('livewire.project.delete-modal');
    }

    public function save()
    {
        $project = Project::where('uuid', $this->uuid)->first();
        if (!$project) {
            Toaster::info('Proyek tidak ditemukan');
            return;
        }

        try {
            if ($project->photo !== null && Storage::exists($project->photo)) {
                Storage::delete($project->photo);
            }
            $project->delete();
            Toaster::success('Proyek berhasil dihapus');
            $this->closeModal();
            $this->dispatch('refresh-list-project');
        } catch (\Throwable $th) {
            Toaster::error('Gagal Hapus. Silakan coba beberapa saat lagi');
        }
    }
}
