<x-slot:title>
    Ubah Proyek
</x-slot:title>


<x-page-layout class="">
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <a href="{{ route('project.index') }}" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Proyek</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Ubah Proyek</span>
        </li>
    </x-slot:breadcrumbs>

    <form wire:submit="save">
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Ubah Proyek</p>
            <button type="submit"
                class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg">Simpan</button>
        </div>
        <div class="mt-5 w-full grid md:grid-cols-4 grid-cols-1 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
            <div class="">
                <label for="" class="block text-sm text-black dark:text-white">Foto Proyek</label>
                <label class="block mt-2">
                    <span class="sr-only">Pilih foto proyek</span>
                    <input type="file"
                        class="block w-full text-sm text-black dark:text-white
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-black dark:file:bg-[#C6C7F8] dark:file:text-black file:text-white
                  hover:file:bg-violet-100
                "
                        wire:model="photo" />
                </label>
                @error('photo')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
                <div class="mt-2">
                    <img src="{{ $photo ? $photo->temporaryUrl() : asset('storage/' . $project->photo) }}"
                        alt="" class="w-100 h-100 object-cover object-center">
                </div>
            </div>
            <div class="md:col-span-3 grid md:grid-cols-2 grid-cols-1 gap-4" x-data="leaflet">
                <div class="">
                    <label for="name" class="text-sm block dark:text-white text-black mb-2">Nama Proyek</label>
                    <input type="text" name="name" id="name" wire:model="name" placeholder="Nama Proyek"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('name')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="address" class="text-sm block dark:text-white text-black mb-2">Alamat</label>
                    <input type="text" name="address" id="address" wire:model="address" placeholder="Alamat"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('address')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="check_in_time" class="text-sm block dark:text-white text-black mb-2">Jam Masuk</label>
                    <input type="time" name="check_in_time" id="check_in_time" wire:model="check_in_time"
                        placeholder="Jam Masuk"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] dark:[color-scheme:dark] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('check_in_time')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="check_out_time" class="text-sm block dark:text-white text-black mb-2">Jam Keluar</label>
                    <input type="time" name="check_out_time" id="check_out_time" wire:model="check_out_time"
                        placeholder="Jam Keluar"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] dark:[color-scheme:dark] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('check_out_time')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="lat" class="text-sm block dark:text-white text-black mb-2">Lattitude</label>
                    <input type="text" name="lat" id="lat" x-model="lat" placeholder="Lattitude"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('lat')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="lng" class="text-sm block dark:text-white text-black mb-2">Longitude</label>
                    <input type="text" name="lng" id="lng" x-model="lng" placeholder="Longitude"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('lng')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="md:col-span-2" wire:key="{{ rand() }}">
                    <label for="address" class="text-sm block dark:text-white text-black mb-2">Titik Lokasi
                        Proyek</label>
                    <div x-ref="map" class="h-96 w-full"></div>
                    @error('address')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </form>
    <livewire:project.table-employee :project="$project" />
</x-page-layout>

@assets
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endassets


@script
    <script>
        Alpine.data('leaflet', () => ({
            lat: @entangle('lat'),
            lng: @entangle('lng'),

            init() {
                var map = L.map(this.$refs.map).setView([0, 117.09], 4);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    setZIndex: 2
                }).addTo(map);

                var popup = L.popup();

                var marker = null
                if (this.lat != null && this.lng !== null) {
                    let latLng = L.latLngBounds([L.latLng(this.lat, this.lng)])
                    map.fitBounds(latLng)
                    marker = L.marker([this.lat, this.lng]).addTo(map)
                }

                map.on('click', (e) => {
                    this.lat = e.latlng.lat
                    this.lng = e.latlng.lng
                })

                this.$watch('lat', () => {
                    if (this.lat != null && this.lng !== null) {
                        if (marker != null) {
                            marker.remove()
                        }
                        marker = L.marker([this.lat, this.lng]).addTo(map)
                    }
                })
                this.$watch('lng', () => {
                    if (this.lat != null && this.lng !== null) {
                        if (marker != null) {
                            marker.remove()
                        }
                        marker = L.marker([this.lat, this.lng]).addTo(map)
                    }
                })
            },
        }))
    </script>
@endscript
