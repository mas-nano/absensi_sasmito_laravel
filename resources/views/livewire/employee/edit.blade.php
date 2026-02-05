<x-slot:title>
    Ubah Pegawai
</x-slot:title>

<x-page-layout>
    <x-slot:breadcrumbs>
        <li class="flex items-center space-x-1">
            <a href="/" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Home</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <a href="/employee" wire:navigate
                class="px-1 py-2 hover:underline text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">Pegawai</a>
        </li>
        <li class="flex items-center space-x-1">
            <span class="px-1 py-2 text-[#1C1C1C66] dark:text-[#FFFFFF66] text-sm">/</span>
            <span class="px-1 py-2 text-black dark:text-white text-sm">Ubah Pegawai</span>
        </li>
    </x-slot:breadcrumbs>

    <form wire:submit="save" x-data="editEmployee">
        <div class="flex justify-between items-center">
            <p class="py-1 px-2 text-sm font-semibold text-black dark:text-white">Ubah Pegawai</p>
            <button type="submit"
                class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg">Simpan</button>
        </div>
        <div class="mt-5 w-full grid md:grid-cols-4 grid-cols-1 p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB] gap-6">
            <div class="">
                <label class="block">
                    <span class="sr-only">Choose profile photo</span>
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
                    <img src="{{ $photo == null ? Storage::url($currentPhoto) : $photo->temporaryUrl() }}"
                        alt="" class="w-100 h-100 object-cover object-center">
                </div>
            </div>
            <div class="md:col-span-3 grid md:grid-cols-2 grid-cols-1 gap-4">
                <div class="">
                    <label for="first_title" class="text-sm block dark:text-white text-black mb-2">Gelar Depan</label>
                    <input type="text" name="first_title" id="first_title" wire:model="first_title"
                        placeholder="Gelar Depan"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('first_title')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="name" class="text-sm block dark:text-white text-black mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" wire:model="name" placeholder="Nama Lengkap"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('name')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="last_title" class="text-sm block dark:text-white text-black mb-2">Gelar Belakang</label>
                    <input type="text" name="last_title" id="last_title" wire:model="last_title"
                        placeholder="Gelar Belakang"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('last_title')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="username" class="text-sm block dark:text-white text-black mb-2">Username</label>
                    <input type="text" name="username" id="username" wire:model="username" placeholder="Username"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('username')
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
                    <label for="phone_number" class="text-sm block dark:text-white text-black mb-2">Nomor
                        Telepon</label>
                    <input type="text" name="phone_number" id="phone_number" wire:model="phone_number"
                        placeholder="Nomor Telepon"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('phone_number')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                    <label for="lunch_price" class="text-sm block dark:text-white text-black mb-2">Uang Makan 1x</label>
                    <input type="text" name="lunch_price" id="lunch_price" wire:model="lunch_price"
                        placeholder="Uang Makan 1x"
                        class="w-full px-3 py-2 border rounded-md dark:border-[#FFFFFF1A] border-[#1C1C1C1A] dark:bg-[#1C1C1CCC] dark:text-white text-black">
                    @error('lunch_price')
                        <span class="text-xs text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="">
                </div>
                <div class="">
                    <label for="phone_number" class="text-sm block dark:text-white text-black mb-2">Reset HP</label>
                    <button type="button"
                        class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg"
                        wire:click="phoneReset">Reset</button>
                </div>
                <div class="">
                    <label for="phone_number" class="text-sm block dark:text-white text-black mb-2">Reset
                        Password</label>
                    <button type="button"
                        class="py-1 px-2 bg-black dark:bg-[#C6C7F8] text-xs text-white dark:text-black rounded-lg"
                        wire:click="passwordReset">Reset</button>
                </div>
            </div>
        </div>
        <div class="mt-5 w-full p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB]">
            <div class="text-sm block dark:text-white text-black mb-2">Hak Akses</div>
            <div class="grid md:grid-cols-3 grid-cols-1 gap-4 mt-2">
                @foreach ($permissionList as $p)
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="permissions[]" id="permissions.{{ $p->id }}"
                            wire:model="permissions.{{ $p->id }}" />
                        <label class="text-sm block dark:text-white text-black"
                            for="permissions.{{ $p->id }}">{{ $p->label }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="mt-5 w-full p-6 rounded-2xl dark:bg-[#FFFFFF0D] bg-[#F7F9FB]">
            <div class="flex justify-between items-center">
                <p class="text-sm block dark:text-white text-black mb-2">Lihat Proyek Lain</p>
                <button type="button" x-on:click="openModal=true"
                    class="py-1 px-2 bg-black dark:bg-[#a4a5f7] text-xs text-white dark:text-black rounded-lg">Tambah
                    Proyek</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr
                        class="text-left border-b-[1px] dark:border-b-[#FFFFFF33] border-b-[#1C1C1C33] text-[#1C1C1C66] dark:text-[#FFFFFF66] text-xs">
                        <th class="py-3 font-normal">No</th>
                        <th class="py-3 font-normal">Nama Proyek</th>
                        <th class="py-3 font-normal">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="projects.length > 0">
                        <template x-for="(project, index) in projects">
                            <tr class="text-black dark:text-white text-xs">
                                <td class="py-3 font-normal" x-text="index + 1"></td>
                                <td class="py-3 font-normal" x-text="project.name"></td>
                                <td class="py-3 font-normal">
                                    <button type="button" x-on:click="deleteRow(project.temp_id)"><i
                                            class="ph-duotone ph-trash text-red-500 text-lg"></i></button>
                                </td>
                            </tr>
                        </template>
                    </template>
                    <template x-if="projects.length == 0">
                        <tr class="text-black dark:text-white text-xs">
                            <td colspan="3" class="py-3 font-normal text-center">Tidak ada data</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="w-screen h-screen bg-black/60 absolute z-10 top-0 left-0 flex justify-center items-center"
            x-show="openModal" x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-on:click.self="openModal=false">
            <div class="max-w-lg w-full p-6  bg-[#F7F9FB] rounded-md sm:p-10" x-show="openModal" x-transition>
                <div class="flex justify-between items-center">
                    <p>Tambah Proyek</p>
                    <button type="button" x-on:click="openModal=false"><i class="ph ph-x"></i></button>
                </div>
                <div class="mt-3">
                    <label for="project_id">Proyek</label>
                    <select name="project_id" id="project_id" x-model="project"
                        class="w-full p-2 border mt-1 border-black rounded-md">
                        <option value="">Pilih Proyek</option>
                        @foreach ($projectList as $p)
                            <option value="{{ $p->id }}~{{ $p->name }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" class=" bg-black text-white py-2 px-4 rounded-md"
                        x-on:click="saveProjectSelected">Simpan</button>
                </div>
            </div>
        </div>
    </form>
</x-page-layout>

@script
    <script>
        Alpine.data('editEmployee', () => ({
            projects: $wire.$entangle('projects'),
            openModal: false,
            project: "",

            init() {

            },

            saveProjectSelected() {
                this.projects.push({
                    temp_id: Math.floor(Math.random() * 1000) + 1,
                    id: this.project.split("~")[0],
                    name: this.project.split("~")[1]
                })
                this.openModal = false
                this.project = ""

            },

            deleteRow(id) {
                this.projects = this.projects.filter(project => project.temp_id != id)
            }
        }))
    </script>
@endscript
