<x-slot:title>
    Proyek
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Proyek</span>
        </li>
    </x-slot:breadcrumbs>

    <div class="flex justify-between items-center">
        <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Proyek</p>
        <a href="{{ route('project.create') }}" wire:navigate
            class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-xs text-white dark:text-black rounded-lg">Tambah
            Proyek</a>
    </div>
    <div class="mt-5 dark:bg-[#FFFFFF0D] bg-[#F7F9FB] rounded-2xl p-6">
        <div class="flex justify-end">
            <input type="text" name="search" id="search" placeholder="Cari"
                wire:model.live.debounce.500ms="search"
                class=" px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
        </div>
        <div class="mt-5 grid md:grid-cols-3 grid-cols-1 gap-7">
            @forelse ($projects as $item)
                <div class="p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#E5ECF680] grid grid-cols-5 gap-4"
                    wire:key="{{ $item->id }}">
                    <div class="col-span-2">
                        <img src="{{ $item->photo != null ? asset('storage/' . $item->photo) : asset('assets/img/no_profile.jpeg') }}"
                            class="w-full object-cover object-center h-full" alt="">
                    </div>
                    <div class="col-span-3 flex flex-col">
                        <div class="flex-1">
                            <p class="text-2xl font-semibold text-black dark:text-white">{{ $item->name }}</p>
                            <p class="text-black dark:text-white text-sm">{{ $item->address }}</p>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('project.show', $item->uuid) }}" wire:navigate><i
                                    class="ph-duotone ph-eye text-blue-500 text-lg"></i></a>
                            <a href="{{ route('project.edit', $item->uuid) }}" wire:navigate><i
                                    class="ph-duotone ph-pencil-simple text-yellow-500 text-lg"></i></a>
                            <button type="button"
                                wire:click="$dispatch('openModal', { component: 'project.delete-modal', arguments:{uuid:'{{ $item->uuid }}'} })"><i
                                    class="ph-duotone ph-trash text-red-500 text-lg"></i></button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex col-span-4 flex-col items-center space-y-3 mt-16">
                    <img src="{{ asset('assets/img/not_found.jpg') }}" alt="" class="w-1/4 rounded-2xl">
                    <p class="text-lg font-semibold text-black dark:text-white">Data tidak ditemukan</p>
                </div>
            @endforelse
        </div>
        {{ $projects->links('vendor.pagination.tailwind') }}
    </div>
</x-page-layout>
