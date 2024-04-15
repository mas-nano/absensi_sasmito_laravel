<tr
    class="text-black dark:text-white print:dark:text-black text-xs print:text-base print:odd:bg-white print:even:bg-slate-50 print:odd:dark:bg-white print:even:dark:bg-slate-50">
    <td class="py-3 print:py-0 font-normal">{{ $user->profile->name }}</td>
    @for ($i = 0; $i < count($listDates); $i++)
        <td class="py-3 print:py-0 font-normal">
            {{-- {{ $user->leaves->where('start_date', '>=', $listDates[$i])->where('to_date', '<=', $listDates[$i])->first()? 'IZ': ($user->attendances->contains('date', $listDates[$i])? '1': '-') }} --}}
            {{ $this->user->leaves->where('start_date', '<=', $listDates[$i])->where('to_date', '>=', $listDates[$i])->where('type', 'Dinas Luar')->first() || $user->attendances->contains('date', $listDates[$i])? '1': '-' }}
        </td>
    @endfor
    <td class="py-3 print:py-0 font-normal">
        {{ $attend }}</td>
    @if (auth()->user()->role_id != 1 && auth()->user()->role_id != 3)
        <td class="py-3 print:py-0 font-normal min-w-16 max-w-16 text-nowrap">
            <input type="text" wire:model.blur="uang_makan"
                class="w-full inline px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black print:bg-transparent print:dark:bg-transparent  print:dark:text-black">
        </td>
        <td class="py-3 print:py-0 font-normal min-w-20">
            <p class="">Rp{{ $total_uang_makan }}</p>
        </td>
        <td class="py-3 print:py-0 font-normal"></td>
    @endif
</tr>
