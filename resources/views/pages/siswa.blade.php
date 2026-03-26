<div class="flex flex-col xl:flex-row h-screen">

    <div x-data="{
        show: false,
        msg: '',
        }"
        x-on:tutup.window="
            msg = $event.detail.message ?? '';
            if (msg) {
                show = true;
                setTimeout(() => show = false, 3000);
            }
        "
        class="fixed top-5 right-5 z-50">
        <div x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="rounded bg-green-700 px-4 py-3 font-bold text-white shadow-lg">
            <span x-text="msg"></span>
        </div>
    </div>

    <aside class="flex flex-col border-r border-r-gray-100 xl:w-96 xl:pr-4">

        <div x-data="{ open: true }" class="flex h-full flex-col">

            <div class="space-y-3">
                <div>
                    <h1 class="text-2xl font-bold text-neutral-900">Data Siswa</h1>
                    {{-- <p class="text-sm text-neutral-600">Grafik kelulusan, grafik absensi per kelas per bulan, dan export laporan.</p> --}}
                </div>

                <div class="flex flex-col gap-2">
                    <livewire:select-kelas />

                    <input type="search" wire:model.live.debounce.250ms="search"
                        class="w-full border border-neutral-200 bg-white px-3 py-2.5 text-sm text-neutral-800 outline-none transition focus:border-amber-900"
                        placeholder="Cari NIS atau nama siswa" />
                </div>
            </div>

            <div class="mt-3 flex cursor-pointer items-center justify-between py-3 xl:hidden" @click="open = !open">
                <span class="text-sm font-semibold text-neutral-700">Daftar Siswa</span>

                <svg :class="{ 'rotate-180': open }" class="h-4 w-4 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <div x-show="open || window.innerWidth >= 1280" x-transition class="flex-1 overflow-y-auto xl:block">
                <livewire:daftar-siswa :id-kelas="$idkelas" :search="$search" />
            </div>

        </div>
    </aside>

    <main class="flex-1 min-w-0 border-t-2 border-t-gray-100 p-3 xl:border-t-0 xl:mt-0 mt-3 md:p-4">
        <livewire:detailsiswa />
    </main>

</div>
