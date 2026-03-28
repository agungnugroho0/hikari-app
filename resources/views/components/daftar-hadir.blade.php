<div class="border-t">
    <x-loading wire:loading ></x-loading>
    @forelse($this->daftarSiswa as $s)
        <div class="md:flex items-center justify-between p-3 border-b">
            <div class="">
                {{ $s->detail->nama_lengkap }}
            </div>
            <div class="flex gap-1 mt-2 md:mt-0">

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'h')"
                    class="px-2 py-1 bg-green-800 text-white rounded active:bg-green-950">
                    Hadir
                </button>

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'm')"
                    class="px-2 py-1 bg-blue-500 text-white rounded active:bg-blue-950">
                    Mensetsu
                </button>

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'i')"
                    class="px-2 py-1 bg-yellow-500 text-white rounded active:bg-yellow-950">
                    Ijin
                </button>

                <button wire:target.attr="disabled" wire:click="absen({{ $s->nis }}, 'a')"
                    class="px-2 py-1 bg-red-500 text-white rounded active:bg-red-950">
                    Alfa
                </button>

            </div>
        </div>
        @empty
        <p class="flex min-h-40 items-center justify-center p-4 text-center text-md text-neutral-500">Siswa sudah absen semua</p>
        @endforelse
</div>