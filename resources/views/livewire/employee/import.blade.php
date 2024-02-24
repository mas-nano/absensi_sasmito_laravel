<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Import Pegawai</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <p>Format excel dapat didownload pada link <a href="{{ asset('assets/excel/format_import_pegawai_excel.xlsx') }}"
            class="text-blue-500">ini</a></p>
    <div class="">
        <label for="" class="block text-sm text-black dark:text-white">Excel Pegawai</label>
        <label class="block">
            <span class="sr-only">Pilih excel pegawai</span>
            <input type="file"
                class="block w-full text-sm
              file:mr-4 file:py-2 file:px-4
              file:rounded-full file:border-0
              file:text-sm file:font-semibold
              file:bg-black dark:file:bg-[#C6C7F8]
              hover:file:bg-violet-100
            "
                wire:model="file" />
        </label>
        @error('file')
            <span class="text-xs text-red-500">{{ $message }}</span>
        @enderror
    </div>
    <div class="flex justify-end space-x-4">
        <button wire:click="$dispatch('closeModal')" type="button">Batal</button>
        <button class="text-blue-500 flex items-center" type="submit"><i class="ph ph-circle-notch hidden animate-spin"
                wire:loading.class.remove="hidden" wire:target="save"></i>Simpan</button>
    </div>
</form>
