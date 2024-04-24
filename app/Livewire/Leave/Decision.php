<?php

namespace App\Livewire\Leave;

use App\Models\Leave;
use LivewireUI\Modal\ModalComponent;
use Toaster;

class Decision extends ModalComponent
{
    public $type;
    public $leave;

    public function mount($leave_id, $type)
    {
        $this->type = $type;

        $this->leave = Leave::find($leave_id);
    }

    public function render()
    {
        return view('livewire.leave.decision');
    }

    public function save()
    {
        try {
            if ($this->type == 'approve') {
                $this->leave->status = 2;
            } else {
                $this->leave->status = 3;
            }
            $this->leave->save();

            $this->dispatch('refresh-list-leave');
            $this->closeModal();
        } catch (\Throwable $th) {
            Toaster::error('Gagal. Silakan coba beberapa saat lagi');
        }
    }
}
