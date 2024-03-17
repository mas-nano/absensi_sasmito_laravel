<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement;
use Livewire\Component;
use App\Traits\UploadFile;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Storage;
use Toaster;

class Edit extends Component
{
    use WithFileUploads, UploadFile;

    #[Validate('required', as: 'Judul')]
    public $title;

    #[Validate('required', as: 'Isi')]
    public $body;

    #[Validate('nullable|file|mimes:pdf', as: 'Lampiran')]
    public $file;

    public $oldFile;

    public $announcement;

    public function mount(Announcement $announcement)
    {
        $this->announcement = $announcement;
        $this->title = $announcement->title;
        $this->body = $announcement->body;
        $this->oldFile = $announcement->attachment;
    }

    public function render()
    {
        return view('livewire.announcement.edit');
    }

    public function save()
    {
        $this->validate();

        try {
            $announcement = Announcement::findOrFail($this->announcement->id);
            $announcement->title = $this->title;
            $announcement->body = $this->body;
            if ($this->file && $announcement->attachment) {
                if (Storage::exists($announcement->attachment)) {
                    Storage::delete($announcement->attachment);
                }
                $announcement->attachment = $this->upload('announcements', $this->file);
            }
            $announcement->save();
            Toaster::success('Pengumuman berhasil disimpan');
            $this->redirectRoute('announcement.index', navigate: true);
        } catch (\Throwable $th) {
            Toaster::error('Gagal simpan. Coba beberapa saat lagi');
        }
    }
}
