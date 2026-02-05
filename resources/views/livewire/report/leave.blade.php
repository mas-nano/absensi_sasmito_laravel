<x-slot:title>
    Detail Laporan
</x-slot:title>
<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <a href="{{ route('report.index') }}" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Laporan</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Detail Laporan</span>
        </li>
    </x-slot:breadcrumbs>

    <div>
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Detail Laporan</p>
            <button class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-xs text-white dark:text-black rounded-lg"
                {{-- wire:click="$dispatch('openModal', { component: 'report.print-report', arguments:{uuid:'{{ $project->uuid }}'}})" --}} x-on:click="window.print()">Cetak
                Laporan
            </button>
        </div>
        <div class="mt-5 dark:bg-[#FFFFFF0D] bg-[#F7F9FB] rounded-2xl p-6">
            <div class="flex justify-between items-center flex-wrap">
                <div class="flex space-x-3">
                    <a wire:navigate href="{{ route('report.show', $project->uuid) }}"
                        class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-sm text-white dark:text-black rounded-lg"
                        x-on:click="show='attendance'">Uang Makan</a>
                    <a wire:navigate href="{{ route('report.attendance', $project->uuid) }}"
                        class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-sm text-white dark:text-black rounded-lg"
                        x-on:click="show='attendance'">Absensi</a>
                    <a wire:navigate href="{{ route('report.leave', $project->uuid) }}"
                        class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-sm text-white dark:text-black rounded-lg"
                        x-on:click="show='leave'">Izin</a>
                </div>
                <input wire:ignore type="text" name="search" id="search" placeholder="Filter Tanggal"
                    class=" px-3 py-2 w-72 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black"
                    date-picker>
            </div>
            <div class="mt-5">
                <table class="w-full">
                    <thead>
                        <tr
                            class="text-left border-b-[1px] dark:border-b-[#FFFFFF33] border-b-[#1C1C1C33] text-[#1C1C1C66] dark:text-[#FFFFFF66] text-xs ">
                            <th class="py-3 font-normal ">No</th>
                            <th class="py-3 font-normal ">Nama</th>
                            <th class="py-3 font-normal ">Tanggal Izin</th>
                            <th class="py-3 font-normal ">Alasan</th>
                            <th class="py-3 font-normal ">Tipe Izin</th>
                            <th class="py-3 font-normal ">Bukti</th>
                            <th class="py-3 font-normal ">Status Izin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($project->leaves as $item)
                            <tr class="text-black dark:text-white text-xs">
                                <td class="py-3 font-normal">{{ $loop->iteration }}</td>
                                <td class="py-3 font-normal">{{ $item->user->name }}</td>
                                <td class="py-3 font-normal">{{ $item->start_date }} - {{ $item->to_date }}
                                </td>
                                <td class="py-3 font-normal">{{ $item->reason }}</td>
                                <td class="py-3 font-normal">{{ $item->type }}</td>
                                <td class="py-3 font-normal">
                                    <a href="{{ Storage::url($item->photo) }}" target="_blank" noreferer noopener>
                                        <i class="ph ph-eye text-xl text-black dark:text-white"></i>
                                    </a>
                                </td>
                                <td class="py-3 font-normal">
                                    {{ ($item->status == 1 ? 'Proses' : $item->status == 2) ? 'Diizinkan' : 'Ditolak' }}
                                </td>
                            </tr>
                        @empty
                            <tr class="text-black dark:text-white text-xs">
                                <td colspan="7" class="py-3 font-normal text-center">Data tidak ada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $project->leaves->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</x-page-layout>

@assets
    <link rel="stylesheet" href="{{ asset('vendor/flatpickr/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <script src="{{ asset('vendor/flatpickr/js/flatpickr.js') }}"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
@endassets

@script
    <script>
        flatpickr($wire.$el.querySelector('[date-picker]'), {
            mode: 'range',
            onClose: (selectedDates) => {
                if (selectedDates.length > 1) {
                    $wire.$set('dates', selectedDates)
                }
            }
        })
    </script>
@endscript
