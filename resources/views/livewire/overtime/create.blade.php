<x-slot:title>
    Tambah Lembur
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <a href="{{ route('overtime.index') }}" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Lembur</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Tambah Lembur</span>
        </li>
    </x-slot:breadcrumbs>

    <form wire:submit="save">
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Tambah Lembur</p>
            <button type="submit"
                class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg">Simpan</button>
        </div>
        <div class="mt-5 w-full grid grid-cols-1 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
            @if ($errors->any())
                <div class="p-4 bg-red-600 rounded-md flex justify-between items-start">
                    <ul class="list-disc list-inside marker:text-white">
                        @foreach ($errors->all() as $error)
                            <li><span class="text-white text-xs">{{ $error }}</span></li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="" wire:ignore>
                <label for="date" class="text-sm block dark:text-white text-black mb-2">Tanggal Lembur</label>
                <input type="text" name="date" id="date" wire:model="date" placeholder="Tanggal Lembur"
                    class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black"
                    x-data="{
                        date: @entangle('date')
                    }" x-init="() => {
                        flatpickr($el, {
                            altInput: true,
                            altFormat: 'd/m/Y',
                            onClose: (selectedDates, dateStr) => {
                                date = dateStr
                            }
                        })
                    }">
                @error('name')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                @foreach ($users as $user)
                    @php
                        $name = [];
                        if ($user->profile?->first_title) {
                            $name[] = $user->profile->first_title;
                        }
                        $name[] = $user->name;
                        if ($user->profile?->last_title) {
                            $name[] = $user->profile->last_title;
                        }
                    @endphp
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="userSelected[]" id="userSelected.{{ $user->id }}"
                            wire:model="userSelected.{{ $user->id }}" />
                        <label class="text-sm block dark:text-white text-black"
                            for="userSelected.{{ $user->id }}">{{ implode(' ', $name) }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
</x-page-layout>

@assets
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/css/flatpickr.min.css') }}">
    <script src="{{ asset('vendor/flatpickr/js/flatpickr.js') }}"></script>
@endassets
