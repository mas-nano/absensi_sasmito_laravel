<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement;
use App\Traits\UploadFile;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Toaster;

class Create extends Component
{
    use WithFileUploads, UploadFile;

    #[Validate('required', as: 'Judul')]
    public $title;

    #[Validate('required', as: 'Isi')]
    public $body;

    #[Validate('nullable|file', as: 'Lampiran')]
    public $file;

    public function render()
    {
        return view('livewire.announcement.create');
    }

    public function save()
    {
        $this->validate();

        try {
            $announcement = new Announcement();
            $announcement->title = $this->title;
            $announcement->body = $this->body;
            if ($this->file) {
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
