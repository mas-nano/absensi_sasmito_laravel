<?php

namespace App\Livewire\Employee;

use App\Models\Profile;
use App\Models\User;
use App\Traits\UploadFile;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Propaganistas\LaravelPhone\PhoneNumber;

class Create extends Component
{
    use WithFileUploads;
    use UploadFile;

    #[Validate('required', as: 'Nama')]
    public $name = null;

    #[Validate('nullable', as: 'Gelar Depan')]
    public $first_title = null;

    #[Validate('nullable', as: 'Gelar Belakang')]
    public $last_title = null;

    #[Validate('required|phone:ID', as: 'Nomor Telepon')]
    public $phone_number = null;

    #[Validate('required|unique:users', as: 'Username')]
    public $username = null;

    #[Validate('required|string|min:12', as: 'Alamat')]
    public $address = null;

    #[Validate('nullable|image|max:2024', as: 'Foto')]
    public $photo = null;

    public function render()
    {
        return view('livewire.employee.create');
    }

    public function save()
    {
        $validated = $this->validate();

        $user = new User();
        $profile = new Profile();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['name'] . '@email.com';
            $user->username = strtolower($validated['username']);
            $user->password = bcrypt('12345678');
            $user->save();

            $phone_number = new PhoneNumber($validated['phone_number'], 'ID');

            $profile->name = $validated['name'];
            $profile->first_title = $validated['first_title'];
            $profile->last_title = $validated['last_title'];
            $profile->phone_number = $phone_number->formatE164();
            $profile->address = $validated['address'];
            $profile->user_id = $user->id;
            if ($this->photo != null) {
                $profile->profile_picture = $this->upload('photo_profile', $this->photo);
            }
            $profile->save();

            Toaster::success('Pegawai berhasil dibuat');
            return $this->redirectRoute('employee.index', navigate: true);
        } catch (\Throwable $th) {
            Toaster::error('Terjadi kesalahan pada sistem, coba ulang beberapa saat lagi ' . $th->getMessage());
        }
    }
}
