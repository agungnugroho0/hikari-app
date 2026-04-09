<div class="border-t border-neutral-200">
    <x-loading wire:loading ></x-loading>
    @forelse($this->daftarSiswa as $s)
        <div class="border-b border-neutral-200 py-3 last:border-b-0 md:flex md:items-center md:justify-between md:gap-4">
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-neutral-900">{{ $s->detail->nama_lengkap }}</p>
                <p class="mt-1 text-xs text-neutral-500">NIS {{ $s->nis }}</p>
            </div>
            <div class="mt-3 flex flex-wrap gap-2 md:mt-0 md:justify-end">

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'h')"
                    class="rounded-xl bg-emerald-700 px-3 py-2 text-sm font-medium text-white transition hover:bg-emerald-600 active:bg-emerald-800">
                    Hadir
                </button>

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'm')"
                    class="rounded-xl bg-sky-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-sky-500 active:bg-sky-700">
                    Mensetsu
                </button>

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'i')"
                    class="rounded-xl bg-amber-500 px-3 py-2 text-sm font-medium text-white transition hover:bg-amber-400 active:bg-amber-600">
                    Ijin
                </button>

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'a')"
                    class="rounded-xl bg-red-900 px-3 py-2 text-sm font-medium text-white transition hover:bg-red-800 active:bg-red-950">
                    Alfa
                </button>

            </div>
        </div>
        @empty
        <p class="flex min-h-40 items-center justify-center rounded-2xl bg-white p-4 text-center text-sm text-neutral-500">Siswa sudah absen semua</p>
        @endforelse
</div>
