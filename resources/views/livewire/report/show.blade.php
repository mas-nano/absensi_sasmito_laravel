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

    <div x-data="{ show: @entangle('show') }">
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
                    <button class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-sm text-white dark:text-black rounded-lg"
                        x-on:click="show='attendance'">Absensi</button>
                    <button class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-sm text-white dark:text-black rounded-lg"
                        x-on:click="show='leave'">Izin</button>
                </div>
                <input wire:ignore type="text" name="search" id="search" placeholder="Filter Tanggal"
                    class=" px-3 py-2 w-72 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black"
                    date-picker>
            </div>
            <div class="mt-5 overflow-x-scroll" x-show="show=='attendance'">
                <table class="w-full print:visible print:top-0 print:left-0 print:absolute" id="table">
                    <thead>
                        <tr>
                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                <th colspan="{{ count($listDates) + 2 }}"
                                    class="text-center text-lg print:text-xl dark:text-white text-black print:dark:text-black">
                                    Rekapitulasi
                                    Absensi Staf</th>
                            @else
                                <th colspan="{{ count($listDates) + 5 }}"
                                    class="text-center dark:text-white text-lg print:text-xl text-black print:dark:text-black">
                                    Rekapitulasi
                                    Uang
                                    Makan Staf</th>
                            @endif
                        </tr>
                        <tr>
                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                <th colspan="{{ count($listDates) + 2 }}"
                                    class="text-center dark:text-white text-lg print:text-xl text-black print:dark:text-black">
                                    Lokasi:
                                    {{ $project->name }}</th>
                            @else
                                <th colspan="{{ count($listDates) + 5 }}"
                                    class="text-center dark:text-white text-lg print:text-xl text-black print:dark:text-black">
                                    Lokasi:
                                    {{ $project->name }}</th>
                            @endif
                        </tr>
                        <tr>
                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                <th colspan="{{ count($listDates) + 2 }}"
                                    class="text-center dark:text-white text-black text-lg print:dark:text-black">
                                    Periode:
                                    {{ \Carbon\Carbon::parse($dates[0])->locale('id_ID')->setTimeZone('Asia/Jakarta')->format('j F Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($dates[1])->locale('id_ID')->setTimeZone('Asia/Jakarta')->format('j F Y') }}
                                </th>
                            @else
                                <th colspan="{{ count($listDates) + 5 }}"
                                    class="text-center dark:text-white text-black text-lg print:dark:text-black">
                                    Periode:
                                    {{ \Carbon\Carbon::parse($dates[0])->locale('id_ID')->setTimeZone('Asia/Jakarta')->format('j F Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($dates[1])->locale('id_ID')->setTimeZone('Asia/Jakarta')->format('j F Y') }}
                                </th>
                            @endif
                        </tr>
                        <tr
                            class="text-left border-b-[1px] dark:border-b-[#FFFFFF33] border-b-[#1C1C1C33] print:dark:border-b-[#1C1C1C33] text-[#1C1C1C66] dark:text-[#FFFFFF66] print:dark:text-black text-xs print:text-base print:font-semibold ">
                            <th class="py-3 font-normal ">Nama</th>
                            @for ($i = 0; $i < count($listDates); $i++)
                                <th class="py-3 font-normal ">
                                    {{ explode('-', $listDates[$i])[2] }}
                                </th>
                            @endfor
                            <th class="py-3 font-normal ">Hari</th>
                            @if (auth()->user()->role_id != 1 && auth()->user()->role_id != 3)
                                <th class="py-3 font-normal ">Uang Makan</th>
                                <th class="py-3 font-normal ">Jumlah</th>
                                <th class="py-3 font-normal ">Tanda Tangan</th>
                            @endif
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $item)
                            <livewire:report.row-table :user="$item" :listDates="$listDates" :key="rand()" />
                        @endforeach
                        @if (auth()->user()->role_id != 1 && auth()->user()->role_id != 3)
                            <livewire:report.grand-total :users="$project->users" :listDates="$listDates" :key="rand()" />
                        @endif
                    </tbody>
                    <tfoot>
                        @if (auth()->user()->role_id != 1 && auth()->user()->role_id != 3)
                            <tr>
                                <td colspan="{{ count($listDates) + 5 }}"
                                    class="dark:text-white text-black print:dark:text-black text-xs">Surabaya,
                                    {{ \Carbon\Carbon::parse($dates[1])->locale('id_ID')->setTimeZone('Asia/Jakarta')->format('j F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="{{ ceil((count($listDates) + 5) / 2) }}"
                                    class="dark:text-white text-black print:dark:text-black text-xs">Dibuat oleh:</td>
                                <td colspan="{{ floor((count($listDates) + 5) / 2) }}"
                                    class="text-center dark:text-white text-black print:dark:text-black text-xs">
                                    Mengetahui:
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="{{ count($listDates) + 5 }}" class="h-24 align-bottom">
                                    <div class="flex justify-between">
                                        <div class="text-center">
                                            <p
                                                class="underline dark:text-white text-black print:dark:text-black text-xs print:font-semibold">
                                                {{ @$project->users->where('role_id', 7)->first()->name }}
                                            </p>
                                            <p class=" dark:text-white text-black print:dark:text-black text-xs">
                                                Logistik
                                            </p>
                                        </div>
                                        <div class="text-center">
                                            <p
                                                class="underline dark:text-white text-black print:dark:text-black text-xs print:font-semibold">
                                                {{ @$project->users->where('role_id', 4)->first()->name }}
                                            </p>
                                            <p class=" dark:text-white text-black print:dark:text-black text-xs">
                                                Cost Control
                                            </p>
                                        </div>
                                        <div class="text-center">
                                            <p
                                                class="underline dark:text-white text-black print:dark:text-black text-xs print:font-semibold">
                                                {{ @$project->users->where('role_id', 3)->first()->name }}
                                            </p>
                                            <p class=" dark:text-white text-black print:dark:text-black text-xs">
                                                Site Manager
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
            <div class="mt-5" x-show="show=='leave'">
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
                        @forelse ($leaves as $item)
                            <tr class="text-black dark:text-white text-xs">
                                <td class="py-3 font-normal">{{ $loop->iteration }}</td>
                                <td class="py-3 font-normal">{{ $item->user->name }}</td>
                                <td class="py-3 font-normal">{{ $item->start_date }} - {{ $item->to_date }}
                                </td>
                                <td class="py-3 font-normal">{{ $item->reason }}</td>
                                <td class="py-3 font-normal">{{ $item->type }}</td>
                                <td class="py-3 font-normal">
                                    <a href="{{ asset('storage/' . $item->photo) }}" target="_blank" noreferer
                                        noopener>
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
                {{ $leaves->links('vendor.pagination.tailwind') }}
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
