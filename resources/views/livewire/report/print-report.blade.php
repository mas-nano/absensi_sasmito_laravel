<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Tambah Karyawan</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <div class="">
        <label for="user_id" class="text-sm block text-black mb-2">Nama Karyawan</label>
        <select type="text" name="user_id" id="user_id" wire:model="user_id" placeholder="Nama Karyawan"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
            <option value="">Semua Karyawan</option>
            @foreach ($users as $item)
                <option value="{{ $item->id }}">{{ $item->profile->first_title }} {{ $item->profile->name }}
                    {{ $item->profile->last_title }}</option>
            @endforeach
        </select>
        @error('user_id')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="date" class="text-sm block  text-black mb-2">Tanggal</label>
        <input wire:ignore type="text" name="search" id="search" placeholder="Tanggal"
            class=" px-3 py-2 w-full border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black"
            date-picker>
        @error('date')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="flex justify-end space-x-4">
        <button class="text-black" type="submit">Simpan</button>
    </div>
</form>

@script
    <script>
        flatpickr($wire.$el.querySelector('[date-picker]'), {
            mode: 'range',
            onClose: (selectedDates) => {
                $wire.$set('dates', selectedDates)
            }
        })
    </script>
@endscript
