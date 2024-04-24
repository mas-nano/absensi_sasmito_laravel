<x-slot:title>
    Tambah Pengumuman
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <a href="/announcement" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Pengumuman</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Tambah Pengumuman</span>
        </li>
    </x-slot:breadcrumbs>

    <form wire:submit="save">
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Tambah Pengumuman</p>
            <button type="submit"
                class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg">Simpan</button>
        </div>
        <div class="mt-5 w-full grid md:grid-cols-4 grid-cols-1 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
            <div class="">
                <label class="text-sm block dark:text-white text-black mb-2">Lampiran</label>
                <label class="block">
                    <span class="sr-only">Choose attachment</span>
                    <input type="file"
                        class="block w-full text-sm text-black dark:text-white
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-black dark:file:bg-[#C6C7F8] dark:file:text-black file:text-white
                  hover:file:bg-violet-100
                "
                        wire:model="file" />
                </label>
                @error('file')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="md:col-span-3 grid grid-cols-1 gap-4">
                <div class="">
                    <label for="title" class="text-sm block dark:text-white text-black mb-2">Judul</label>
                    <input type="text" name="title" id="title" wire:model="title" placeholder="Judul"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('title')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="body" class="text-sm block dark:text-white text-black mb-2">Isi</label>
                    <textarea name="body" id="body" wire:model="body" placeholder="Isi" rows="4"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black"></textarea>
                    @error('body')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</x-page-layout>
