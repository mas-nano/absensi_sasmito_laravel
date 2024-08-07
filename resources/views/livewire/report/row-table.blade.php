<tr
    class="text-black dark:text-white print:dark:text-black text-xs print:text-base print:odd:bg-white print:even:bg-slate-50 print:odd:dark:bg-white print:even:dark:bg-slate-50">
    <td class="py-3 print:py-0 font-normal">{{ $user->profile->name }}</td>
    @for ($i = 0; $i < count($listDates); $i++)
        <td class="py-3 print:py-0 font-normal">
            {{-- {{ $user->leaves->where('start_date', '>=', $listDates[$i])->where('to_date', '<=', $listDates[$i])->first()? 'IZ': ($user->attendances->contains('date', $listDates[$i])? '1': '-') }} --}}
            {{ ($this->user->leaves->where('start_date', '<=', $listDates[$i])->where('to_date', '>=', $listDates[$i])->where('type', 'Dinas Luar')->first()? '1': !$this->user->leaves->where('start_date', '<=', $listDates[$i])->where('to_date', '>=', $listDates[$i])->whereIn('type', ['Sakit', 'Lainnya'])->first() && $user->attendances->contains('date', $listDates[$i]))? '1': '-' }}
        </td>
    @endfor
    <td class="py-3 print:py-0 font-normal">
        {{ $attend }}</td>
    @if (auth()->user()->role_id != 1 && auth()->user()->role_id != 3)
        <td class="py-3 print:py-0 font-normal min-w-16 max-w-16 text-nowrap">
            Rp{{ number_format($user->profile->lunch_price, 0, '.', '.') }}
        </td>
        <td class="py-3 print:py-0 font-normal min-w-20">
            <p class="">Rp {{ $total_uang_makan }}</p>
        </td>
        <td class="py-3 print:py-0 font-normal"></td>
    @endif
</tr>
