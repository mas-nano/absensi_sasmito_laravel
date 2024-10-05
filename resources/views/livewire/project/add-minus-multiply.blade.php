<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Tambah Jam Pengurangan Uang Makan</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <div class="">
        <label for="minus_time_limit" class="text-sm block text-black mb-2">Jam Masuk</label>
        <input type="time" name="minus_time_limit" wire:model="minus_time_limit" id="minus_time_limit"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
        @error('minus_time_limit')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="minus" class="text-sm block text-black mb-2">Uang Makan Dikurangi?</label>
        <input type="tel" name="minus" wire:model="minus" id="minus"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
        @error('minus')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label class="text-sm block text-black mb-2">Hari</label>
        <div class="grid grid-cols-4 gap-2">
            @foreach ($daysText as $key => $value)
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="{{ $value }}" wire:model="days.{{ $key }}" />
                    <label for="{{ $value }}" class="text-sm text-black">{{ $value }}</label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="flex justify-end space-x-4">
        <button class="text-black" type="submit" wire:loading.attr="disabled">Simpan</button>
    </div>
</form>
