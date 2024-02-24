<?php

namespace App\Livewire\Employee;

use App\Models\Profile;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    public $search;

    #[On('refresh-list-employee')]
    public function refresh()
    {
    }

    public function render()
    {
        if ($this->search) {
            $this->resetPage();
        }
        $employees = Profile::with('user')->where('name', 'ilike', '%' . $this->search . '%')->paginate(10);
        return view('livewire.employee.index', [
            'employees' => $employees
        ]);
    }
}
