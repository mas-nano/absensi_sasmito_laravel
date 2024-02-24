<x-slot:breadcrumbs>
    <li class="flex items-center space-x-1">
        <a href="/" wire:navigate
            class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
    </li>
    <li class="flex items-center space-x-1">
        <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
        <a href="/employee" wire:navigate
            class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Pegawai</a>
    </li>
    <li class="flex items-center space-x-1">
        <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
        <span class="px-1 py-2 text-black dark:text-white text-sm">Detail Pegawai</span>
    </li>
</x-slot:breadcrumbs>

<x-slot:title>
    Tambah Pegawai
</x-slot:title>

<div>
    <div class="flex justify-between items-center">
        <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Detail Pegawai</p>
    </div>
    <div class="mt-5 w-full grid grid-cols-4 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
        <div class="">
            <img src="{{ $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : asset('assets/img/no_profile.jpeg') }}"
                alt="" class="w-100 h-100 object-cover object-center">
        </div>
        <div class="col-span-3 grid grid-cols-2 gap-4">
            <div class="">
                <label for="first_title" class="text-sm block dark:text-white text-black mb-2">Gelar Depan</label>
                <p class="text-black dark:text-white">{{ $profile->first_title }}</p>
            </div>
            <div class="">
                <label for="name" class="text-sm block dark:text-white text-black mb-2">Nama Lengkap</label>
                <p class="text-black dark:text-white">{{ $profile->name }}</p>
            </div>
            <div class="">
                <label for="last_title" class="text-sm block dark:text-white text-black mb-2">Gelar Belakang</label>
                <p class="text-black dark:text-white">{{ $profile->last_title }}</p>
            </div>
            <div class="">
                <label for="username" class="text-sm block dark:text-white text-black mb-2">Username</label>
                <p class="text-black dark:text-white">{{ $profile->user->username }}</p>
            </div>
            <div class="">
                <label for="address" class="text-sm block dark:text-white text-black mb-2">Alamat</label>
                <p class="text-black dark:text-white">{{ $profile->address }}</p>
            </div>
            <div class="">
                <label for="phone_number" class="text-sm block dark:text-white text-black mb-2">Nomor Telepon</label>
                <p class="text-black dark:text-white">{{ $profile->phone_number }}</p>
            </div>
        </div>
    </div>
</div>
