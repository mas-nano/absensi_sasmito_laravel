<form wire:submit="save" class="p-6 flex flex-col space-y-4">
    <div class="flex justify-between items-center">
        @if ($type == 'approve')
            <p>Setujui Izin</p>
        @else
            <p>Tolak Izin</p>
        @endif
        <button wire:click="$dispatch('closeModal')" type="button"><i class="ph ph-x"></i></button>
    </div>
    @if ($type == 'approve')
        <p>Izin yang bersangkutan akan disetujui. Apakah Anda yakin ?</p>
    @else
        <p>Izin yang bersangkutan akan ditolak. Apakah Anda yakin ?</p>
    @endif
    <div class="flex justify-end space-x-4">
        <button wire:click="$dispatch('closeModal')" type="button">Batal</button>
        <button class="text-blue-500" type="submit">Ya</button>
    </div>
</form>
