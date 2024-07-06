<?php

namespace App\Livewire\Position;

use App\Models\Permission;
use App\Models\Position;
use App\Models\User;
use DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Toaster;

class Edit extends Component
{
    public ?Position $position;

    public $name;

    public $permission = [];

    public function mount(Position $position)
    {
        $this->position = $position;

        $this->name = $position->name;

        foreach ($position->permissions->pluck('id')->toArray() as $p) {
            $this->permission[$p] = true;
        };
    }

    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('positions')->ignore($this->position->id)],
        ];
    }

    public function validationAttributes()
    {
        return [
            'name' => 'Nama Jabatan'
        ];
    }

    public function render()
    {
        return view('livewire.position.edit', [
            'permissions' => Permission::all()
        ]);
    }

    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->position->name = $this->name;
            $this->position->save();

            $this->permission = array_filter($this->permission, fn ($value) => $value === true);

            $this->position->permissions()->sync(array_keys($this->permission));

            $users = User::where('position_id', $this->position->id)->get();
            foreach ($users as $user) {
                $user->permissions()->sync(array_keys($this->permission));
            }

            DB::commit();

            Toaster::success('Jabatan berhasil diubah');
            $this->redirectRoute('position.index', navigate: true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Toaster::error('Gagal simpan. Coba beberapa saat lagi');
        }
    }
}
