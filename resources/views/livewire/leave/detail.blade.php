<x-slot:title>
    Perizinan
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Perizinan</span>
        </li>
    </x-slot:breadcrumbs>

    <div class="flex justify-between items-center">
        <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Perizinan</p>
    </div>
    <div class="mt-5 dark:bg-[#FFFFFF0D] bg-[#F7F9FB] rounded-2xl p-6 overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr
                    class="text-left border-b-[1px] dark:border-b-[#FFFFFF33] border-b-[#1C1C1C33] text-[#1C1C1C66] dark:text-[#FFFFFF66] text-xs">
                    <th class="py-3 px-2 font-normal text-nowrap ">No.</th>
                    <th class="py-3 px-2 font-normal text-nowrap ">Nama</th>
                    <th class="py-3 px-2 font-normal text-nowrap ">Jenis Izin</th>
                    <th class="py-3 px-2 font-normal text-nowrap ">Tanggal</th>
                    <th class="py-3 px-2 font-normal text-nowrap ">Keterangan</th>
                    <th class="py-3 px-2 font-normal text-nowrap ">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leaves as $item)
                    <tr class="text-black dark:text-white text-xs">
                        <td class="py-3 px-2 font-normal text-nowrap">{{ $loop->iteration }}</td>
                        <td class="py-3 px-2 font-normal text-nowrap">{{ $item->user->name }}</td>
                        <td class="py-3 px-2 font-normal text-nowrap">{{ $item->type }}</td>
                        <td class="py-3 px-2 font-normal text-nowrap">
                            {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($item->to_date)->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-2 font-normal">{{ $item->reason }}</td>
                        <td class="py-3 px-2 flex space-x-2">
                            @if ($item->status == 2)
                                <p class="bg-green-500 text-white px-2 py-1 rounded-sm font-normal">Disetujui</p>
                            @elseif ($item->status == 3)
                                <p class="bg-red-500 text-white px-2 py-1 rounded-sm font-normal">Ditolak</p>
                            @else
                                <button
                                    wire:click="$dispatch('openModal', { component: 'leave.decision', arguments:{leave_id:{{ $item->id }}, type:'approve'}})"><i
                                        class="ph ph-check-circle text-green-500 text-lg"></i></button>
                                <button
                                    wire:click="$dispatch('openModal', { component: 'leave.decision', arguments:{leave_id:{{ $item->id }}, type:'decline'}})"><i
                                        class="ph ph-x-circle text-red-500 text-lg"></i></button>
                            @endif
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
</x-page-layout>
