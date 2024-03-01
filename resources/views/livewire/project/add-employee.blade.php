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
    <div class="">
        <label for="role_id" class="text-sm block  text-black mb-2">Jenis Karyawan</label>
        <select type="text" name="role_id" id="role_id" wire:model="role_id" placeholder="Jenis Karyawan"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
            <option value="">-- Pilih Jenis Karyawan --</option>
            @foreach ($roles as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @error('role_id')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="">
        <label for="position_id" class="text-sm block  text-black mb-2">Jabatan</label>
        <select type="text" name="position_id" id="position_id" wire:model="position_id" placeholder="Jabatan"
            class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
            <option value="">-- Pilih Jabatan --</option>
            @foreach ($positions as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @error('position_id')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="flex justify-end space-x-4">
        <button class="text-black" type="submit">Simpan</button>
    </div>
</form>
