<?php

namespace App\Livewire\Project;

use App\Models\Project;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class TableEmployee extends Component
{
    public $project_id;

    #[On('refresh-list-employee-project')]
    public function refresh()
    {
    }

    public function mount($project)
    {
        $this->project_id = $project->id;
    }

    public function render()
    {
        return view('livewire.project.table-employee', [
            'project' => Project::with('users.profile', 'users.role')->where('id', $this->project_id)->first()
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
