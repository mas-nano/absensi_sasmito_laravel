<x-slot:breadcrumbs>
    <li class="flex items-center space-x-1">
        <a href="/" wire:navigate
            class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
    </li>
    <li class="flex items-center space-x-1">
        <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
        <span class="px-1 py-2 text-black dark:text-white text-sm">Role</span>
    </li>
</x-slot:breadcrumbs>

<x-slot:title>
    Role
</x-slot:title>

<div>
    <div class="flex justify-between items-center">
        <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Role</p>
    </div>
    <div class="mt-5 dark:bg-[#FFFFFF0D] bg-[#F7F9FB] rounded-2xl p-6">
        <div class="flex justify-end">
            <input type="text" name="search" id="search" placeholder="Cari"
                wire:model.live.debounce.500ms="search"
                class=" px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
        </div>
        <table class="w-full">
            <thead>
                <tr
                    class="text-left border-b-[1px] dark:border-b-[#FFFFFF33] border-b-[#1C1C1C33] text-[#1C1C1C66] dark:text-[#FFFFFF66] text-xs ">
                    <th class="py-3 font-normal ">No.</th>
                    <th class="py-3 font-normal ">Nama</th>
                    <th class="py-3 font-normal ">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $item)
                    <tr class="text-black dark:text-white text-xs">
                        <td class="py-3 font-normal">{{ $loop->iteration }}</td>
                        <td class="py-3 font-normal">{{ $item->name }}</td>
                        <td class="py-3 flex space-x-2">
                            <a href="{{ route('role.edit', $item->uuid) }}" wire:navigate><i
                                    class="ph-duotone ph-pencil-simple text-yellow-500 text-lg"></i></a>
                            <button><i class="ph-duotone ph-trash text-red-500 text-lg"></i></button>
                        </td>
                    </tr>
                @empty
                    <tr class="text-black dark:text-white text-xs">
                        <td colspan="3" class="py-3 font-normal text-center">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
