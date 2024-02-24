<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Tambah Karyawan</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <div class="">
        <label for="user_id" class="text-sm block  text-black mb-2">Nama Karyawan</label>
        <select type="text" name="user_id" id="user_id" wire:model="user_id" placeholder="Nama Karyawan"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
            <option value="">-- Pilih Pegawai --</option>
            @foreach ($profiles as $item)
                <option value="{{ $item->user->id }}">{{ $item->first_title }} {{ $item->name }}
                    {{ $item->last_title }}</option>
            @endforeach
        </select>
        @error('user_id')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="flex justify-end space-x-4">
        <button wire:click="$dispatch('closeModal')" type="button">Batal</button>
        <button class="text-blue-500" type="submit">Simpan</button>
    </div>
</form>
