<?php

namespace App\Livewire\Setting;

use Auth;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    public function render()
    {
        return view('livewire.setting.index');
    }

    public function logout()
    {
        try {
            Auth::guard('web')->logout();
            return $this->redirectRoute('login', navigate: true);
        } catch (\Throwable $th) {
            Toaster::error('Gagal Logout');
        }
    }
}
