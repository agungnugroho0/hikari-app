<div>
    <div x-data="{
        show: false,
        msg: '',
        fireworkTimer: null,
        launchFireworks() {
            const duration = 3000;
            const end = Date.now() + duration;
            clearInterval(this.fireworkTimer);
            this.fireworkTimer = setInterval(() => {
                if (Date.now() > end) {
                    clearInterval(this.fireworkTimer);
                    this.fireworkTimer = null;
                    return;
                }
                if (typeof confetti === 'function') {
                    confetti({
                        particleCount: 50,
                        spread: 80,
                        origin: { x: Math.random(), y: Math.random() * 0.5 }
                    });
                }
            }, 220);
        }
    }"
        x-on:tutupforms.window="
            msg = $event.detail.message ?? '';
            if (msg) {
                show = true;
                setTimeout(() => show = false, 3000);
            }
            if ($event.detail.celebrate) {
                launchFireworks();
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
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <div class="mb-2">
            <h1 class="text-2xl font-bold text-neutral-900">Job Order</h1>
            {{-- <p class="text-sm text-neutral-600">Grafik kelulusan, grafik absensi per kelas per bulan, dan export laporan.</p> --}}
    </div>
    @if ($bukaform)
        <x-kembali wire:click="tutupforms" />
        <livewire:form.insert_jobfair />
    @elseif ($editjob)
        <x-kembali wire:click="tutupforms" />
        <livewire:form.edit-job-fair />
    @elseif ($tambahpesertaform)
        <button wire:click="tutupforms"
            class="px-3 py-2 bg-slate-100 rounded shadow hover:bg-slate-200 cursor-pointer transition-all">Kembali
        </button>
        <livewire:form.tambahpesertaforms />
    @elseif ($lolosform)
        <x-kembali wire:click="tutupforms" />
        <livewire:form.lolos-form />
    @else
        <button wire:click="bukaforms"
            class="px-3 py-2 bg-slate-100 rounded shadow hover:bg-slate-200 cursor-pointer transition-all mb-3">+
            Job
            Order
        </button>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-3 flex-wrap justify-evenly">
            @foreach ($so as $item)
                <div class="rounded shadow " x-data="{ open: false }">
                    <h3 class="md:hidden xl:block bg-gray-100 p-1 font-bold">{{ $item->nama_so }}</h3>
                    <div class="xl:hidden flex items-center justify-between p-3 cursor-pointer" @click="open = !open">
                        <span>{{ $item->nama_so }}</span>

                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div x-show="open || window.innerWidth >= 1280" x-transition class="flex-1 xl:block ">
                        @foreach ($item->list_job as $job)
                            <div class="bg-gray-50 p-1 flex">
                                <p class="font-normal">{{ $job->nama_job }}</p>

                                <div x-data="{ open: false }" class="ml-auto relative pl-5">
                                    <button @click.stop="open = !open">
                                        ---
                                    </button>

                                    <div x-show="open" @click.outside="open = false" x-transition
                                        class="absolute right-0 mt-2 z-10 bg-white rounded shadow shadow-gray-200 w-40">
                                        <ul class="p-2 text-sm">
                                            <li>
                                                <div wire:click.stop="editjobs('{{ $job->id_job }}')"
                                                    class="px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                                    Edit
                                                </div>
                                            </li>
                                            <li>
                                                <div wire:click.stop="confirmDelete('{{ $job->id_job }}')"
                                                    class="px-2 py-1 hover:bg-gray-100 cursor-pointer text-red-600">
                                                    Delete
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div wire:click.stop="tambahpeserta('{{ $job->id_job }}')"
                                class="p-2 font-regular italic hover:bg-gray-100 cursor-pointer">Tambah Peserta</div>
                            @foreach ($job->list_ww as $l)
                                <div class="flex gap-1 relative">
                                    <p class="px-2">{{ $loop->iteration }}</p>
                                    <p>{{ $l->corelist->detail->nama_lengkap }}</p>
                                    <div class="ml-auto flex gap-3">
                                        <button type="button"
                                            wire:click.stop="lolos('{{ $l->corelist->nis }}','{{ $job->id_job }}')"
                                            class="cursor-pointer hover:text-green-700 text-2xl leading-none">
                                            &check;
                                        </button>
                                        <button type="button"
                                            wire:click.stop="gagal('{{ $l->corelist->nis }}','{{ $job->id_job }}')"
                                            class="cursor-pointer hover:text-red-500 text-2xl leading-none">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <x-loading wire:loading wire:target="tutupforms,bukaforms,editjobs,tambahpeserta,lolos,gagal,confirmDelete"></x-loading>

    {{-- <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" wire:loading>
        <div role="status">
            <svg aria-hidden="true"
                class="w-8 h-8 text-neutral-tertiary animate-spin fill-red-900 mx-auto translate-y-5"
                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor" />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill" />
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
    </div> --}}

    @if ($showConfirm)
        <div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl p-6 w-80 text-center">
                <h2 class="text-lg font-bold mb-3 text-red-600">
                    Hapus Job?
                </h2>

                <p class="text-sm text-gray-600 mb-5">
                    Semua peserta wawancara dalam job ini juga akan terhapus.
                    Tindakan ini tidak bisa dibatalkan.
                </p>

                <div class="flex gap-3 justify-center">
                    <button wire:click="$set('showConfirm', false)"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Batal
                    </button>

                    <button wire:click="deletejobs" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
