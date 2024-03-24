<?php

namespace App\Livewire\Employee;

use App\Models\Profile;
use App\Models\User;
use App\Traits\UploadFile;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Propaganistas\LaravelPhone\PhoneNumber;
use Storage;

class Edit extends Component
{
    use WithFileUploads;
    use UploadFile;

    public ?Profile $profile;
    public $name = '';
    public $first_title = '';
    public $last_title = '';
    public $phone_number = '';
    public $username = '';
    public $address = '';
    public $photo = null;
    public $currentPhoto = null;

    public function mount(Profile $profile): void
    {
        $profile->load('user');
        $this->name = $profile->name;
        $this->first_title = $profile->first_title;
        $this->last_title = $profile->last_title;
        $this->phone_number = $profile->phone_number;
        $this->username = $profile->user->username;
        $this->address = $profile->address;
        $this->currentPhoto = $profile->profile_picture;
        $this->profile = $profile;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'first_title' => 'nullable',
            'last_title' => 'nullable',
            'phone_number' => 'required|phone:ID',
            'username' => ['required', Rule::unique('users')->ignore($this->profile->user)],
            'address' => 'required|min:12',
            'photo' => 'nullable|image|max:2048'
        ];
    }

    public function validationAttribute()
    {
        return [
            'name' => 'Nama Lengkap',
            'phone_number' => 'Nomor Telepon',
            'username' => 'Username',
            'address' => 'Alamat',
            'photo' => 'Foto Profil'
        ];
    }

    public function render()
    {
        return view('livewire.employee.edit');
    }

    public function save()
    {
        $validated = $this->validate();

        $user = $this->profile->user;

        try {
            $user->name = $validated['name'];
            $user->username = strtolower($validated['username']);
            $user->save();

            $phone_number = new PhoneNumber($validated['phone_number'], 'ID');

            $this->profile->name = $validated['name'];
            $this->profile->first_title = $validated['first_title'];
            $this->profile->last_title = $validated['last_title'];
            $this->profile->phone_number = $phone_number->formatE164();
            $this->profile->address = $validated['address'];
            $this->profile->user_id = $user->id;
            if ($this->photo != null && !is_string($this->photo)) {
                if ($this->profile->profile_picture !== null && Storage::exists($this->profile->profile_picture)) {
                    Storage::delete($this->profile->profile_picture);
                }
                $this->profile->profile_picture = $this->upload('photo_profile', $this->photo);
            }
            $this->profile->save();

            Toaster::success('Pegawai berhasil diubah');
            return $this->redirectRoute('employee.index', navigate: true);
        } catch (\Throwable $th) {
            Toaster::error('Terjadi kesalahan pada sistem, coba ulang beberapa saat lagi ' . $th->getMessage());
        }
    }

    public function phoneReset()
    {
        try {
            $user = User::find($this->profile->user_id);
            $user->device_id = null;
            $user->save();

            Toaster::success('HP berhasil di reset');
        } catch (\Throwable $th) {
            Toaster::error('Terjadi kesalahan pada sistem, coba beberapa saat lagi.');
        }
    }

    public function passwordReset()
    {
        try {
            $user = User::find($this->profile->user_id);
            $user->password = bcrypt(12345678);
            $user->save();

            Toaster::success('Password berhasil di reset. Password: 12345678');
        } catch (\Throwable $th) {
            Toaster::error('Terjadi kesalahan pada sistem, coba beberapa saat lagi.');
        }
    }
}
