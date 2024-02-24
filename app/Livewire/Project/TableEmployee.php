<?php

namespace App\Livewire\Project;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class TableEmployee extends Component
{
    public $project;

    #[On('refresh-list-employee-project')]
    public function refresh()
    {
    }

    public function mount($project)
    {
        $this->project = $project;
    }

    public function render()
    {
        return view('livewire.project.table-employee', [
            'users' => $this->project->users
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            Toaster::error('User tidak ditemukan');
            return;
        }

        $user->role_id = null;
        $user->project_id = null;
        $user->save();

        $this->dispatch('refresh-list-employee-project')->self();
    }
}
