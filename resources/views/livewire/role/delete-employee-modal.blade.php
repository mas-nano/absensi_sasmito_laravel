<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Hapus User</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <p>User akan tidak memiliki role yang mengakibatkan user tidak bisa login aplikasi. Apakah Anda yakin ?</p>
    <div class="flex justify-end space-x-4">
        <button wire:click="$dispatch('closeModal')" type="button">Batal</button>
        <button class="text-red-500" type="submit">Hapus</button>
    </div>
</form>
