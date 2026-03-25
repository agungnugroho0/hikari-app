<div>
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
            class="bg-green-700 font-bold text-white px-4 py-3 rounded shadow-lg">
            <span x-text="msg"></span>
        </div>
    </div>
    <x-loading wire:loading wire:target="mode,muat"></x-loading>
    <div>
            <h1 class="text-2xl font-bold text-neutral-900">Staff</h1>
            {{-- <p class="text-sm text-neutral-600">Grafik kelulusan, grafik absensi per kelas per bulan, dan export laporan.</p> --}}
    </div>
    @if ($metode === 'form')
        <x-kembali wire:click="muat"/>
        <livewire:form.tambah-staff />
        @elseif ($metode === 'edit')
        <x-kembali wire:click="muat"/>
        <livewire:form.edit-staff :id="$staff_id"/>
    @else
        <button wire:click="mode('form')" class="px-3 py-2 bg-slate-100 rounded shadow hover:bg-slate-200 cursor-pointer transition-all mb-3">+ Staff </button>
        <div class="grid gap-5 grid-cols-3">
        @foreach ($staff as $s)
            <div class="bg-gray-100 rounded p-5 hover:bg-gray-50">
                <div class="flex">
                <img src="{{ $s->foto_s ? Storage::url($s->foto_s) : Storage::url('foto/foto.jpeg') }}" alt="foto_s" class="rounded-full w-12 h-12 object-cover">
                <div x-data="{ open: false }" class="ml-auto relative">

                            <button @click.stop="open = !open">
                                ---
                            </button>

                            <div x-show="open" @click.outside="open = false" x-transition
                                class="absolute right-0 mt-2 z-10 bg-white rounded shadow shadow-gray-200 w-40">
                                <ul class="p-2 text-sm">
                                    <li>
                                        <div wire:click.stop="mode('edit', {{ $s->id_staff }})"
                                            class="px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                            Edit
                                        </div>
                                    </li>
                                    <li>
                                        <div wire:click.stop="confirmDelete('{{ $s->id_staff }}')"
                                            class="px-2 py-1 hover:bg-gray-100 cursor-pointer text-red-300">
                                            Delete
                                        </div>
                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>
                <p class="font-bold">{{ $s->nama_s }}</p>
                <p>Username : <span>{{$s->username}}</span></p>
                <p>Akses : <span>{{$s->akses}}</span></p>
            </div>
        @endforeach
        </div>
    @endif

    {{-- konfirmasi delete --}}
    @if ($showConfirm)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-80 text-center">

                <h2 class="text-lg font-bold mb-3 text-red-600">
                    ⚠ Hapus Staff?
                </h2>

                <div class="flex gap-3 justify-center">

                    <button wire:click="$set('showConfirm', false)"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Batal
                    </button>

                    <button wire:click="deletestaff" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Ya, Hapus
                    </button>

                </div>
            </div>
        </div>
    @endif
</div>
