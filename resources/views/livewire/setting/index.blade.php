<x-slot:title>
    Setting
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Setting</span>
        </li>
    </x-slot:breadcrumbs>
    <div class="dark:bg-[#FFFFFF0D] bg-[#F7F9FB] rounded-2xl p-6">
        @if (auth()->user()->role_id == 1)
            <div class="my-3">
                <p class="text-black dark:text-white font-semibold text-xl mb-3">Toleransi Keterlambatan</p>
                <input type="text" name="name" id="name" wire:model.blur="late" placeholder="0"
                    class="w-1/12 px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                <span class="dark:text-white text-black">Menit</span>
            </div>
            <div class="my-3">
                <p class="text-black dark:text-white font-semibold text-xl mb-3">Pergantian Absen</p>
                <input type="time" name="name" id="name" wire:model.blur="time" placeholder="0"
                    class="w-1/6 px-3 py-2 border rounded-md dark:[color-scheme:dark] dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
            </div>
        @endif
        <div class="my-3">
            <p class="text-black dark:text-white font-semibold text-xl mb-3">Logout</p>
            <button class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-xs text-white dark:text-black rounded-lg"
                wire:click="logout">Logout</button>
        </div>
    </div>
</x-page-layout>
