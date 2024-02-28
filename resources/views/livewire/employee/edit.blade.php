<x-slot:title>
    Ubah Pegawai
</x-slot:title>

<x-page-layout>
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
            <span class="px-1 py-2 text-black dark:text-white text-sm">Ubah Pegawai</span>
        </li>
    </x-slot:breadcrumbs>

    <form wire:submit="save">
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Ubah Pegawai</p>
            <button type="submit"
                class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg">Simpan</button>
        </div>
        <div class="mt-5 w-full grid grid-cols-4 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
            <div class="">
                <label class="block">
                    <span class="sr-only">Choose profile photo</span>
                    <input type="file"
                        class="block w-full text-sm text-black dark:text-white
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-black dark:file:bg-[#C6C7F8] dark:file:text-black file:text-white
                  hover:file:bg-violet-100
                "
                        wire:model="photo" />
                </label>
                @error('photo')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
                <div class="mt-2">
                    <img src="{{ $photo == null ? asset('storage/' . $currentPhoto) : $photo->temporaryUrl() }}"
                        alt="" class="w-100 h-100 object-cover object-center">
                </div>
            </div>
            <div class="col-span-3 grid grid-cols-2 gap-4">
                <div class="">
                    <label for="first_title" class="text-sm block dark:text-white text-black mb-2">Gelar Depan</label>
                    <input type="text" name="first_title" id="first_title" wire:model="first_title"
                        placeholder="Gelar Depan"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('first_title')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="name" class="text-sm block dark:text-white text-black mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" wire:model="name" placeholder="Nama Lengkap"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('name')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="last_title" class="text-sm block dark:text-white text-black mb-2">Gelar Belakang</label>
                    <input type="text" name="last_title" id="last_title" wire:model="last_title"
                        placeholder="Gelar Belakang"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('last_title')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="username" class="text-sm block dark:text-white text-black mb-2">Username</label>
                    <input type="text" name="username" id="username" wire:model="username" placeholder="Username"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('username')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="address" class="text-sm block dark:text-white text-black mb-2">Alamat</label>
                    <input type="text" name="address" id="address" wire:model="address" placeholder="Alamat"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('address')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="phone_number" class="text-sm block dark:text-white text-black mb-2">Nomor
                        Telepon</label>
                    <input type="text" name="phone_number" id="phone_number" wire:model="phone_number"
                        placeholder="Nomor Telepon"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('phone_number')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</x-page-layout>
