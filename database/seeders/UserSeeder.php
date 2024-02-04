<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Admin';
        $user->username = 'admin';
        $user->email = 'admin@email.com';
        $user->password = bcrypt('12345678');
        $user->role_id = 1;
        $user->save();
    }
}
