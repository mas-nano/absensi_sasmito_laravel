<?php

namespace App\Livewire;

use Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Login extends Component
{
    #[Validate('required|min:3')]
    public $username;

    #[Validate('required|min:3')]
    public $password;

    #[Layout('components.layouts.app')]
    #[Title('Login')]
    public function render()
    {
        return view('livewire.login');
    }

    public function login()
    {
        $validated = $this->validate();

        if (Auth::guard('web')->attempt($validated)) {
            if (Auth::guard('web')->user()->role_id !== 1) {
                Auth::guard('web')->logout();
                Toaster::error('Gagal Login!');
                return;
            }
            return $this->redirectIntended('/dashboard', true);
        }
        Toaster::error('Email atau password Anda salah');
        return $this->redirect('/', true);
    }
}
