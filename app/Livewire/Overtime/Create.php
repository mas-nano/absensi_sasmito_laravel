<?php

namespace App\Livewire\Overtime;

use App\Models\Overtime;
use App\Models\User;
use DB;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Toaster;

class Create extends Component
{
    #[Validate('required|array', as: 'Pegawai')]
    public $userSelected = [];

    #[Validate('required|date', as: 'Tanggal Lembur')]
    public $date;

    public function render()
    {
        return view('livewire.overtime.create', [
            'users' => User::query()
                ->with('profile')
                ->when(auth()->user()->hasPermission('view-own-project'), function ($query) {
                    return $query->where('project_id', auth()->user()->project_id);
                })
                ->when(
                    !auth()->user()->hasPermission('view-own-project') &&
                        auth()->user()->hasPermission('view-other-project'),
                    function ($query) {
                        $projectArr = [];
                        array_push($projectArr, auth()->user()->project_id);
                        array_push($projectArr, ...auth()->user()->projects->pluck('id')->toArray());
                        $query->whereIn('project_id', $projectArr);
                    }
                )
                ->get()
        ]);
    }

    public function save()
    {
        $this->validate();

        if ($this->date < date('Y-m-d')) {
            throw ValidationException::withMessages([
                'date' => 'Tanggal Lembur tidak boleh kurang dari tanggal sekarang'
            ]);
        }

        $userSelectedFiltered = collect($this->userSelected)->filter(function ($item) {
            return $item != false;
        });

        if ($userSelectedFiltered->count() == 0) {
            throw ValidationException::withMessages([
                'userSelected' => 'Pilih minimal 1 pegawai'
            ]);
        }

        $errorUserOvertimeExists = [];

        foreach ($userSelectedFiltered as $key => $user) {
            $overtimeExists = Overtime::query()
                ->where('user_id', $key)
                ->where('date', $this->date)
                ->first();

            if ($overtimeExists) {
                $user = User::find($key);
                $errorUserOvertimeExists['userSelected.' . $key] = 'Lembur untuk ' . $user->name . ' ini sudah ada';
            }
        }

        if (count($errorUserOvertimeExists) > 0) {
            throw ValidationException::withMessages($errorUserOvertimeExists);
        }

        DB::beginTransaction();
        try {
            foreach ($userSelectedFiltered as $key => $user) {
                $user = User::find($key);
                Overtime::create([
                    'user_id' => $key,
                    'date' => $this->date,
                    'project_id' => $user->project_id,
                ]);
            }

            DB::commit();
            Toaster::success('Lembur berhasil ditambahkan');
            return redirect()->route('overtime.index');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            dd($th);
            Toaster::error('Lembur gagal ditambahkan');
        }
    }
}
