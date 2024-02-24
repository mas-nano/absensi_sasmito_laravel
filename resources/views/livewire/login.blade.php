<div class="w-screen h-screen flex relative">
    <img src="{{ asset('assets/img/bg.png') }}" class="w-full h-full object-cover object-center absolute z-0 top-0 left-0"
        alt="">
    <div class="w-1/2 z-10"></div>
    <div class="w-1/2 h-full flex justify-center items-center z-10">
        <div class="flex flex-col max-w-md p-6 rounded-md sm:p-10 dark:bg-gray-900 dark:text-gray-100">
            <div class="mb-8 text-center">
                <h1 class="my-3 text-4xl font-bold">Sign in</h1>
                <p class="text-sm dark:text-gray-400">Sign in to access your account</p>
            </div>
            <form novalidate="" action="" class="space-y-12" wire:submit="login">
                <div class="space-y-4">
                    <div>
                        <label for="username" class="block mb-2 text-sm">Username</label>
                        <input type="username" name="username" id="username" wire:model="username"
                            class="w-full px-3 py-2 border rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                        @error('username')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="password" class="text-sm">Password</label>
                        </div>
                        <input type="password" name="password" id="password" placeholder="*****" wire:model="password"
                            class="w-full px-3 py-2 border rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                    </div>
                </div>
                <div class="space-y-2">
                    <div>
                        <button type="submit"
                            class="w-full px-8 py-3 font-semibold rounded-md dark:bg-violet-400 dark:text-gray-900">Sign
                            in</button>
                    </div>
                    <p class="px-6 text-sm text-center dark:text-gray-400">Don't have an account yet?
                        <a rel="noopener noreferrer" href="#" class="hover:underline dark:text-violet-400">Sign
                            up</a>.
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
