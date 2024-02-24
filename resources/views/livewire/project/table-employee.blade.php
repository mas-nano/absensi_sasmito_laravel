<div class="mt-5 w-full grid grid-cols-2 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB]">
    @if (!request()->routeIs('project.show'))
        <div class="col-span-2">
            <button class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg"
                type="button"
                wire:click="$dispatch('openModal', { component: 'project.add-employee', arguments:{project_id:{{ $project->id }}}})">Tambah
                Pegawai</button>
        </div>
    @endif
    <table class="">
        <thead>
            <tr
                class="text-left border-b-[1px] dark:border-b-[#FFFFFF33] border-b-[#1C1C1C33] text-[#1C1C1C66] dark:text-[#FFFFFF66] text-xs ">
                <th class="py-3 font-normal ">No.</th>
                <th class="py-3 font-normal ">Nama</th>
                <th class="py-3 font-normal ">Jenis Karyawan</th>
                @if (!request()->routeIs('project.show'))
                    <th class="py-3 font-normal ">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $item)
                <tr class="text-black dark:text-white text-xs">
                    <td class="py-3 font-normal">{{ $loop->iteration }}</td>
                    <td class="py-3 font-normal">{{ $item->profile->first_title }} {{ $item->profile->name }}
                        {{ $item->profile->last_title }}</td>
                    <td class="py-3 font-normal">{{ $item->role->name }}</td>
                    @if (!request()->routeIs('project.show'))
                        <td class="py-3">
                            <button wire:click="destroy('{{ $item->id }}')"><i
                                    class="ph-duotone ph-trash text-red-500 text-lg"></i></button>
                        </td>
                    @endif
                </tr>
            @empty
                <tr class="text-black dark:text-white text-xs">
                    <td colspan="3" class="py-3 font-normal text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
