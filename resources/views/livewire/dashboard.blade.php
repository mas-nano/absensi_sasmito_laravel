<x-slot:title>
    Overview
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Home</span>
        </li>
    </x-slot:breadcrumbs>

    <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Overview</p>
    <div class="mt-5 grid md:grid-cols-4 grid-cols-2 gap-7">
        <div class="p-6 rounded-2xl bg-[#E3F5FF] flex flex-col space-y-2">
            <p class="text-sm text-black">Views</p>
            <p class="text-2xl font-semibold text-black">7.265</p>
        </div>
        <div class="p-6 rounded-2xl bg-[#E3F5FF] flex flex-col space-y-2">
            <p class="text-sm text-black">Views</p>
            <p class="text-2xl font-semibold text-black">7.265</p>
        </div>
        <div class="p-6 rounded-2xl bg-[#E3F5FF] flex flex-col space-y-2">
            <p class="text-sm text-black">Views</p>
            <p class="text-2xl font-semibold text-black">7.265</p>
        </div>
        <div class="p-6 rounded-2xl bg-[#E3F5FF] flex flex-col space-y-2">
            <p class="text-sm text-black">Views</p>
            <p class="text-2xl font-semibold text-black">7.265</p>
        </div>
        <div class="p-6 rounded-2xl bg-[#E3F5FF] flex flex-col space-y-2">
            <p class="text-sm text-black">Views</p>
            <p class="text-2xl font-semibold text-black">7.265</p>
        </div>
        <div class="p-6 rounded-2xl bg-[#E3F5FF] flex flex-col space-y-2">
            <p class="text-sm text-black">Views</p>
            <p class="text-2xl font-semibold text-black">7.265</p>
        </div>
    </div>
</x-page-layout>
