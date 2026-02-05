<x-slot:title>
    Detail Pengumuman
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
            <span class="px-1 py-2 text-black dark:text-white text-sm">Detail Pengumuman</span>
        </li>
    </x-slot:breadcrumbs>

    <div>
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Detail Pengumuman</p>
        </div>
        <div class="mt-5 w-full grid md:grid-cols-4 grid-cols-1 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
            <div class="">
                <label class="text-sm block dark:text-white text-black mb-2">Lampiran</label>
                @if ($announcement->attachment)
                    <a href="{{ Storage::url($announcement->attachment) }}"
                        class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg">Lihat</a>
                @endif
            </div>
            <div class="md:col-span-3 grid grid-cols-1 gap-4">
                <div class="">
                    <label for="title" class="text-sm block dark:text-white text-black mb-2">Judul</label>
                    <input type="text" name="title" id="title" value="{{ $announcement->title }}"
                        placeholder="Judul"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                </div>
                <div class="">
                    <label for="body" class="text-sm block dark:text-white text-black mb-2">Isi</label>
                    <textarea name="body" id="body" placeholder="Isi" rows="4"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">{{ $announcement->body }}</textarea>
                </div>
            </div>
        </div>
    </div>
</x-page-layout>
