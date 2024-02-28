<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Ubah Jabatan</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <div class="">
        <label for="name" class="text-sm block  text-black mb-2">Nama Jabatan</label>
        <input type="text" name="name" id="name" wire:model="name" placeholder="Nama Jabatan"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
        @error('name')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="flex justify-end space-x-4">
        <button wire:click="$dispatch('closeModal')" type="button">Batal</button>
        <button class="text-blue-500" type="submit">Simpan</button>
    </div>
</form>
