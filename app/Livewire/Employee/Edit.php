<?php

namespace App\Livewire\Employee;

use App\Models\Permission;
use App\Models\Profile;
use App\Models\Project;
use App\Models\User;
use App\Traits\UploadFile;
use DB;
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
    public $lunch_price = '';
    public $photo = null;
    public $currentPhoto = null;
    public $permissions = [];
    public $projects = [];

    public function mount(Profile $profile): void
    {
        $this->name = $profile->name;
        $this->first_title = $profile->first_title;
        $this->last_title = $profile->last_title;
        $this->phone_number = $profile->phone_number;
        $this->username = $profile->user->username;
        $this->address = $profile->address;
        $this->lunch_price = $profile->lunch_price;
        $this->currentPhoto = $profile->profile_picture;
        $this->profile = $profile;
        $this->profile->user->permissions()->each(function ($permission) {
            $this->permissions[$permission->id] = true;
        });
        $this->profile->user->projects()->each(function ($project) {
            $this->projects[] = [
                'temp_id' => rand(),
                'id' => $project->id,
                'name' => $project->name
            ];
        });
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
            'lunch_price' => 'required|numeric',
            'photo' => 'nullable|image|max:2048',
            'permissions' => 'array',
            'projects' => 'array'
        ];
    }

    public function validationAttribute()
    {
        return [
            'name' => 'Nama Lengkap',
            'phone_number' => 'Nomor Telepon',
            'username' => 'Username',
            'address' => 'Alamat',
            'lunch_price' => 'Uang Makan 1x',
            'photo' => 'Foto Profil'
        ];
    }

    public function render()
    {
        return view('livewire.employee.edit', [
            'permissionList' => Permission::all(),
            'projectList' => Project::all()
        ]);
    }

    public function save()
    {
        $validated = $this->validate();

        $user = $this->profile->user;

        DB::beginTransaction();
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
            $this->profile->lunch_price = $validated['lunch_price'];
            $this->profile->user_id = $user->id;
            if ($this->photo != null && !is_string($this->photo)) {
                if ($this->profile->profile_picture !== null && Storage::exists($this->profile->profile_picture)) {
                    Storage::delete($this->profile->profile_picture);
                }
                $this->profile->profile_picture = $this->upload('photo_profile', $this->photo);
            }
            $this->profile->save();

            $permissionSelected = array_filter($validated['permissions'], fn ($permission) => $permission == true);

            $user->permissions()->sync(array_keys($permissionSelected));
            $projectSelected = array_filter($validated['projects'], fn ($project) => $project['id'] != $user->project_id);
            $user->projects()->sync(array_column($projectSelected, 'id'));
            DB::commit();
            Toaster::success('Pegawai berhasil diubah');
            return $this->redirectRoute('employee.index', navigate: true);
        } catch (\Throwable $th) {
            DB::rollBack();
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
            $user->password = bcrypt("12345678");
            $user->save();

            Toaster::success('Password berhasil di reset. Password: 12345678');
        } catch (\Throwable $th) {
            Toaster::error('Terjadi kesalahan pada sistem, coba beberapa saat lagi.');
        }
    }
}
