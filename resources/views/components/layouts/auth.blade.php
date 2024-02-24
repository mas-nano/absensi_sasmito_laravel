<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-all duration-150" x-data="{ darkMode: window.matchMedia('(prefers-color-scheme: dark)').matches }"
    x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @livewireStyles
    @vite('resources/js/app.js')
    <title>{{ $title ?? 'Page Title' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/fill/style.css" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/regular/style.css" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.0.3/src/duotone/style.css" />
</head>

<body>
    <div class="w-screen h-screen flex dark:bg-[#1C1C1C]">
        <nav
            class="w-[212px] border-r-[1px] border-r-[#1C1C1C1A] p-4 dark:border-r-[#FFFFFF1A] flex-col flex space-y-5">
            <div class="p-2 flex items-center space-x-2">
                <img src="https://picsum.photos/1000" alt=""
                    class="w-6 h-6 rounded-full object-cover object-center">
                <p class="text-sm text-black dark:text-white">Nabil Islam</p>
            </div>
            <div class="flex-col flex space-y-3">
                <p class="text-sm text-[#1C1C1C66] dark:text-[#FFFFFF66] py-1 px-3">Dashboard</p>
                <div class="flex space-x-1 items-center p-2 rounded-lg bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]">
                    <div class="w-4 h-4"></div>
                    <i class="ph-fill ph-chart-pie-slice text-xl text-white"></i>
                    <p class="text-black dark:text-white text-sm">Overview</p>
                </div>
                <a href="{{ route('report.index') }}" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('report*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="h-4 w-4"></div>
                    <i class="ph-fill ph-files text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Laporan</p>
                </a>
                <div class="flex space-x-1 items-center p-2 rounded-lg">
                    <i class="ph ph-caret-right dark:text-[#FFFFFF33] text-[#1C1C1C33]"></i>
                    <i class="ph-duotone ph-folder text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Projects</p>
                </div>
            </div>
            <div class="flex-col flex space-y-3">
                <p class="text-sm text-[#1C1C1C66] dark:text-[#FFFFFF66] py-1 px-3">Master</p>
                <div class="flex space-x-1 items-center p-2 rounded-lg bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]">
                    <i class="ph ph-caret-down dark:text-[#FFFFFF33] text-[#1C1C1C33]"></i>
                    <i class="ph-duotone ph-identification-badge text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">User Profile</p>
                </div>
                <div class="flex-col flex space-y-3">
                    <div class="flex space-x-1 items-center p-2 rounded-lg">
                        <div class="w-4 h-4"></div>
                        <div class="w-5 h-5"></div>
                        <p class="text-black dark:text-white text-sm">Account</p>
                    </div>
                </div>
                <a href="/employee" wire:navigate
                    class="flex space-x-1 items-center p-2 rounded-lg hover:bg-[#1C1C1C0D] hover:dark:bg-[#FFFFFF1A] {{ Route::is('employee*') ? 'bg-[#1C1C1C0D] dark:bg-[#FFFFFF1A]' : '' }}">
                    <div class="w-4 h-4"></div>
                    <i class="ph-duotone ph-users-three text-xl text-black dark:text-white"></i>
                    <p class="text-black dark:text-white text-sm">Pegawai</p>
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
        </nav>
        <div class="w-full overflow-auto">
            <header
                class="p-5 flex justify-between items-center border-b-[1px] border-b-[[#1C1C1C1A] dark:border-b-[#FFFFFF1A]">
                <ol class="flex space-x-2">
                    {{ $breadcrumbs }}
                </ol>
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

    @livewire('wire-elements-modal')
    <x-toaster-hub />
    @livewireScriptConfig
</body>

</html>
