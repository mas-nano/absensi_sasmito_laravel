<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'create-employee', 'label' => 'Buat Pegawai'],
            ['name' => 'edit-employee', 'label' => 'Ubah Pegawai'],
            ['name' => 'delete-employee', 'label' => 'Hapus Pegawai'],
            ['name' => 'view-employee', 'label' => 'Lihat Pegawai'],
            ['name' => 'create-project', 'label' => 'Buat Proyek'],
            ['name' => 'edit-project', 'label' => 'Ubah Proyek'],
            ['name' => 'delete-project', 'label' => 'Hapus Proyek'],
            ['name' => 'view-project', 'label' => 'Lihat Proyek'],
            ['name' => 'view-other-project', 'label' => 'Lihat Proyek Lain'],
            ['name' => 'view-own-project', 'label' => 'Lihat Proyek Sendiri'],
            ['name' => 'create-position', 'label' => 'Buat Jabatan'],
            ['name' => 'edit-position', 'label' => 'Ubah Jabatan'],
            ['name' => 'delete-position', 'label' => 'Hapus Jabatan'],
            ['name' => 'view-position', 'label' => 'Lihat Jabatan'],
            ['name' => 'edit-setting', 'label' => 'Ubah Pengaturan'],
            ['name' => 'view-cost-report', 'label' => 'Lihat Laporan Uang Makan'],
            ['name' => 'view-attendance-report', 'label' => 'Lihat Laporan Absen'],
            ['name' => 'view-leave-report', 'label' => 'Lihat Laporan Izin'],
            ['name' => 'view-own-attendance-report', 'label' => 'Lihat Laporan Absen Sendiri'],
            ['name' => 'create-attendance', 'label' => 'Buat Absen'],
            ['name' => 'create-leave', 'label' => 'Buat Izin'],
            ['name' => 'manage-leave', 'label' => 'Kelola Izin'],
            ['name' => 'view-news', 'label' => 'Lihat Pengumuman'],
            ['name' => 'create-news', 'label' => 'Buat Pengumuman'],
            ['name' => 'edit-news', 'label' => 'Ubah Pengumuman'],
            ['name' => 'delete-news', 'label' => 'Hapus Pengumuman'],
            ['name' => 'create-free-attendance', 'label' => 'Buat Absen Bebas'],
        ]);

        $newPermissions = collect();

        $permissions->each(function ($permission) use ($newPermissions) {
            $newPermission = new Permission();
            $newPermission->name = $permission['name'];
            $newPermission->label = $permission['label'];
            $newPermission->save();

            $newPermissions->push($newPermission);
        });
    }
}
