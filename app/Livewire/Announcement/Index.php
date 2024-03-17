<?php

namespace App\Livewire\Announcement;

use App\Models\Announcement;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    #[On('announcement-refresh')]
    public function refresh()
    {
    }

    public function render()
    {
        return view('livewire.announcement.index', [
            'announcements' => Announcement::paginate(10)
        ]);
    }
}
