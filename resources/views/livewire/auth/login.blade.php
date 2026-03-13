<div class="h-screen flex flex-col">
    <div class="flex-1 flex justify-center items-center">
        <div class="relative">
            <!-- GRADIENT GLOW -->
            <div class="absolute -inset-10
                        bg-gradient-to-r from-purple-200 via-[#fbacbd] to-[#fec8d5]
                        opacity-40 blur-3xl rounded-2xl pointer-events-none">
            </div>

            <!-- CARD -->
            <section class="relative grid gap-2 grid-cols-1 md:grid-cols-2 w-full md:max-w-4xl
                            rounded-xl p-5 md:bg-white shadow-xl h-125 bg-transparent z-10">
                
                {{-- kiri --}}
                <div class="hidden md:block md:relative">
                    <div class="flex gap-2 pt-1 pl-1">
                        <img src="{{ asset('img/logo.png') }}" alt="logo" class="max-w-10 relative -top-1">
                        <span class="font-semibold ">Hikari Gakkou</span>
                    </div>
                    <img src="{{ asset('img/dashboard.png') }}" alt="dashboard" class="absolute inset-10 w-7xl -left-5">
                    <p class="text-sm text-gray-300 absolute bottom-0">© Copyright 2026. Hak cipta dilindungi Undang-undang </p>
                </div>
                {{-- kanan --}}
                <div class="mb-3 self-center ">
                    <form wire:submit.prevent="login" class="p-6 pb-0 justify-center relative z-10">
                    <img src="{{ asset('img/logo.png') }}" alt="logo" class="md:hidden mx-auto max-w-24 relative -top-1">
                    <h1 class="text-lg font-bold mb-4 text-center md:-top-8 md:relative text-shadow-amber-500">Login</h1>
                    <input type="text" wire:model.defer = 'username' placeholder="username" class="w-full md:w-96 rounded-xl border border-gray-200 p-3 mb-3 bg-white">
                    @error('username')
                        <p class="relative -top-2 text-sm pl-2 mb-2 text-red-400">{{$message}}</p>
                    @enderror
                    <input type="password" wire:model.defer = 'password' placeholder="password" class="w-full md:w-96 rounded-xl border border-gray-200 p-3 mb-3 bg-white">
                    @error('password')
                        <p class="relative -top-2 text-sm pl-2 mb-2 text-red-400">{{$message}}</p>
                    @enderror

                    <button type="submit" class="w-full bg-orange-700 text-white py-2 rounded-full cursor-pointer hover:bg-red-600 z-10"> Masuk </button>
                    @error('login')
                        <p class="w-full text-sm pl-2 mb-2 text-red-400">{{$message}}</p>
                    @enderror
                </form>
                <span class="text-xs text-gray-500 p-8 ">Tidak bisa login, hubungi tim</span>
                <p class="text-sm text-gray-400 absolute bottom-5 right-6 md:hidden pointer-events-none">© Copyright 2026. Hak cipta dilindungi Undang-undang </p>
                </div>

            </section>
        </div>
    </div>
</div>
