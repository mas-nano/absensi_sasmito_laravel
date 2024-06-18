<?php

declare(strict_types=1);

namespace App\Livewire\Project;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class TableOvertime extends Component
{
    #[Locked]
    public int $project_id;

    #[Reactive]
    public $overtimeLimit;

    public function mount($overtimeLimit, $project_id): void
    {
        $this->project_id = $project_id;
        $this->overtimeLimit = $overtimeLimit;
    }

    public function render(): View
    {
        return view('livewire.project.table-overtime');
    }
}
