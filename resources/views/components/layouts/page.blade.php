<div class="w-screen h-screen flex dark:bg-[#1C1C1C] relative" x-data="{ sidebar: false }">
    <nav
        class="w-[212px] border-r-[1px] border-r-[#1C1C1C1A] p-4 dark:border-r-[#FFFFFF1A]  hidden flex-col md:flex space-y-5">
        <div class="p-2 flex items-center space-x-2">
            <img src="https://picsum.photos/1000" alt="" class="w-6 h-6 rounded-full object-cover object-center">
            <p class="text-sm text-black dark:text-white">{{ auth()->user()->name }}</p>
        </div>
        <div class="flex-col flex space-y-3">
            <p class="text-sm text-[#1C1C1C66] dark:text-[#FFFFFF66] py-1 px-3">Dashboard</p>
            <a href="{{ route('dashboard') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('dashboard*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="w-4 h-4"></div>
                <i class="ph-fill ph-chart-pie-slice text-xl text-white"></i>
                <p class="text-black dark:text-white text-sm">Overview</p>
            </a>
            <a href="{{ route('report.index') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('report*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="h-4 w-4"></div>
                <i class="ph-fill ph-files text-xl text-black dark:text-white"></i>
                <p class="text-black dark:text-white text-sm">Laporan</p>
            </a>
            <a href="{{ route('leave.index') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('leave*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="h-4 w-4"></div>
                <i class="ph-fill ph-files text-xl text-black dark:text-white"></i>
                <p class="text-black dark:text-white text-sm">Perizinan</p>
            </a>
            @if (auth()->user()->role_id == 1)
                <a href="{{ route('announcement.index') }}" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('announcement*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="h-4 w-4"></div>
                    <i class="ph ph-newspaper text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Pengumuman</p>
                </a>
            @endif
            <a href="{{ route('setting.index') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('setting*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="h-4 w-4"></div>
                <i class="ph-duotone ph-gear text-xl text-black dark:text-white"></i>
                <p class="text-black dark:text-white text-sm">Settings</p>
            </a>
        </div>
        @if (auth()->user()->role_id == 1)
            <div class="flex-col flex space-y-3">
                <p class="text-sm text-[#1C1C1C66] dark:text-[#FFFFFF66] py-1 px-3">Master</p>
                <a href="/employee" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('employee*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-users-three text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Pegawai</p>
                </a>
                <a href="/position" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('position*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-briefcase text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Jabatan</p>
                </a>
                <a href="/project" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('project*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-folder text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Proyek</p>
                </a>
                <a href="{{ route('role.index') }}" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('role*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-shield text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Role</p>
                </a>
            </div>
        @endif
    </nav>
    <nav class="w-[212px] border-r-[1px] border-r-[#1C1C1C1A] h-screen transition-all duration-200 p-4 dark:border-r-[#FFFFFF1A] dark:bg-black bg-white md:hidden flex-col flex space-y-5 absolute z-auto"
        x-bind:class="sidebar ? '-translate-x-0' : '-translate-x-56'">
        <div class="p-2 flex items-center justify-between">
            <div class="flex items-center  space-x-2">
                <img src="https://picsum.photos/1000" alt=""
                    class="w-6 h-6 rounded-full object-cover object-center">
                <p class="text-sm text-black dark:text-white">{{ auth()->user()->name }}</p>

            </div>
            <button x-on:click="sidebar = false" class="flex items-center">
                <i class="ph ph-x text-xl dark:text-white text-black"></i>
            </button>
        </div>
        <div class="flex-col flex space-y-3">
            <p class="text-sm text-[#1C1C1C66] dark:text-[#FFFFFF66] py-1 px-3">Dashboard</p>
            <a href="{{ route('dashboard') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('dashboard*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="w-4 h-4"></div>
                <i class="ph-fill ph-chart-pie-slice text-xl text-white"></i>
                <p class="text-black dark:text-white text-sm">Overview</p>
            </a>
            <a href="{{ route('report.index') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('report*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="h-4 w-4"></div>
                <i class="ph-fill ph-files text-xl text-black dark:text-white"></i>
                <p class="text-black dark:text-white text-sm">Laporan</p>
            </a>
            <a href="{{ route('leave.index') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('leave*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="h-4 w-4"></div>
                <i class="ph-fill ph-files text-xl text-black dark:text-white"></i>
                <p class="text-black dark:text-white text-sm">Perizinan</p>
            </a>
            @if (auth()->user()->role_id == 1)
                <a href="{{ route('announcement.index') }}" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('announcement*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="h-4 w-4"></div>
                    <i class="ph ph-newspaper text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Pengumuman</p>
                </a>
            @endif
            <a href="{{ route('setting.index') }}" wire:navigate
                class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('setting*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                <div class="h-4 w-4"></div>
                <i class="ph-duotone ph-gear text-xl text-black dark:text-white"></i>
                <p class="text-black dark:text-white text-sm">Settings</p>
            </a>
        </div>
        @if (auth()->user()->role_id == 1)
            <div class="flex-col flex space-y-3">
                <p class="text-sm text-[#1C1C1C66] dark:text-[#FFFFFF66] py-1 px-3">Master</p>
                <a href="/employee" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('employee*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-users-three text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Pegawai</p>
                </a>
                <a href="/position" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('position*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-briefcase text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Jabatan</p>
                </a>
                <a href="/project" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('project*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-folder text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Proyek</p>
                </a>
                <a href="{{ route('role.index') }}" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('role*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-shield text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Role</p>
                </a>
            </div>
        @endif
    </nav>
    <div class="w-full overflow-auto">
        <header
            class="p-5 flex justify-between items-center border-b-[1px] border-b-[[#1C1C1C1A] dark:border-b-[#FFFFFF1A]">
            <div class="flex">
                <button x-on:click="sidebar = !sidebar">
                    <i class="ph ph-list text-xl text-black dark:text-white"></i>
                </button>
                <ol class="flex space-x-2">
                    {{ $breadcrumbs }}
                </ol>
            </div>
            <button x-on:click="darkMode = !darkMode">
                <i class="ph-duotone text-xl text-black dark:text-white"
                    x-bind:class="darkMode ? 'ph-sun' : 'ph-moon'"></i>
            </button>
        </header>
        <main class="p-7">
            {{ $slot }}
        </main>
    </div>
</div>
