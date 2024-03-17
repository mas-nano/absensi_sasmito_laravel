<?php

namespace App\Livewire\Setting;

use App\Models\Setting;
use Auth;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Index extends Component
{
    public $late;
    public $time;

    public function mount()
    {
        $settings = Setting::all();
        $this->late = $settings->where('field', 'late')->first()->value ?? null;
        $this->time = $settings->where('field', 'time')->first()->value ?? null;
    }

    public function updated($property)
    {
        if ($property === 'late') {
            $late = Setting::where('field', 'late')->first();
            if ($late) {
                $late->value = $this->late;
                $late->save();
            } else {
                $late = new Setting();
                $late->field = 'late';
                $late->value = $this->late;
                $late->save();
            }
        }
        if ($property === 'time') {
            $time = Setting::where('field', 'time')->first();
            if ($time) {
                $time->value = $this->time;
                $time->save();
            } else {
                $time = new Setting();
                $time->field = 'time';
                $time->value = $this->time;
                $time->save();
            }
        }
    }

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
