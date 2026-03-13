<div class="flex flex-col xl:flex-row h-screen">

    {{-- SIDEBAR SISWA --}}
    <div class="border-r flex flex-col border-r-gray-100 xl:w-96 xl:pr-4">

        <div x-data="{ open: true }" class="flex flex-col h-full">

            {{-- HEADER FILTER --}}
            <div class="flex shadow-xs">

                <livewire:select-kelas />

                <input type="search" wire:model.live.debounce.250ms="search"
                    class="w-full px-2 bg-neutral-secondary-medium border border-default-medium text-heading text-sm"
                    placeholder="Search" />
            </div>


            {{-- TITLE ACCORDION (MOBILE ONLY) --}}
            <div class="xl:hidden flex items-center justify-between p-3 cursor-pointer" @click="open = !open">

                <span class="font-semibold text-sm">Daftar Siswa</span>

                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>


            {{-- LIST SISWA --}}
            <div x-show="open || window.innerWidth >= 1280" x-transition class="flex-1 overflow-y-auto xl:block mt-2">
                <livewire:daftar-siswa :id-kelas="$idkelas" :search="$search" />
            </div>

        </div>
    </div>


    {{-- DETAIL --}}
    <main class="flex-1 p-4 border-t-gray-100 border-t-2 xl:border-t-0 xl:mt-0 mt-3">
        <livewire:detailsiswa />
    </main>

</div>
