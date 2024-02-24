<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        <p>Hapus Karyawan</p>
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    <p>Karyawan beserta data yang dimiliki karyawan tersebut ikut terhapus. Apakah Anda yakin ?</p>
    <div class="flex justify-end space-x-4">
        <button wire:click="$dispatch('closeModal')" type="button">Batal</button>
        <button class="text-red-500" type="submit">Hapus</button>
    </div>
</form>
