<x-slot:title>
    Tambah Jabatan
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <a href="{{ route('position.index') }}" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Jabatan</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Tambah Jabatan</span>
        </li>
    </x-slot:breadcrumbs>

    <form wire:submit="save">
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Tambah Jabatan</p>
            <button type="submit"
                class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg">Simpan</button>
        </div>
        <div class="mt-5 w-full grid grid-cols-1 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
            <div class="">
                <label for="name" class="text-sm block dark:text-white text-black mb-2">Nama Jabatan</label>
                <input type="text" name="name" id="name" wire:model="name" placeholder="Nama Jabatan"
                    class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                @error('name')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                @foreach ($permissions as $p)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="permission[]" id="permission.{{ $p->id }}"
                            wire:model="permission.{{ $p->id }}" />
                        <label class="text-sm block dark:text-white text-black"
                            for="permission.{{ $p->id }}">{{ $p->label }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
</x-page-layout>
