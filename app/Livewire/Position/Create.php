<?php

namespace App\Livewire\Position;

use App\Models\Permission;
use App\Models\Position;
use DB;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class Create extends Component
{
    #[Validate('required|unique:positions')]
    public $name;

    public $permission = [];

    public function render()
    {
        return view('livewire.position.create', [
            'permissions' => Permission::all(),
        ]);
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $position = new Position();
            $position->name = $this->name;
            $position->save();

            $position->permissions()->sync(array_keys($this->permission));
            DB::commit();

            Toaster::success('Jabatan berhasil disimpan');
            $this->redirectRoute('position.index', navigate: true);
        } catch (\Throwable $th) {
            DB::rollBack();
            Toaster::error('Gagal simpan. Coba beberapa saat lagi');
        }
    }
}
