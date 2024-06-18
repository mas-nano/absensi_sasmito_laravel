<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Tambah Jam Lembur</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <div class="">
        <label for="check_out_time_limit" class="text-sm block text-black mb-2">Jam Keluar</label>
        <input type="time" name="check_out_time_limit" wire:model="check_out_time_limit" id="check_out_time_limit"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
        @error('check_out_time_limit')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="multiply" class="text-sm block text-black mb-2">Uang Makan Dikali?</label>
        <input type="tel" name="multiply" wire:model="multiply" id="multiply"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
        @error('multiply')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="flex justify-end space-x-4">
        <button class="text-black" type="submit" wire:loading.attr="disabled">Simpan</button>
    </div>
</form>
