<?php

namespace App\Imports;

use App\Models\Profile;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportProfile implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = new User();
        $user->name = $row['nama_lengkap'];
        $user->username = strtolower(str_replace('.', '', join('_', explode(' ', $row['nama_lengkap'])))) . rand();
        $user->email = strtolower(str_replace('.', '', join('_', explode(' ', $row['nama_lengkap'])))) . rand() . '@email.com';
        $user->password = bcrypt('12345678');
        $user->save();

        return new Profile([
            'first_title' => $row['gelar_depan'],
            'name' => $row['nama_lengkap'],
            'last_title' => $row['gelar_belakang'],
            'user_id' => $user->id
        ]);
    }
}
