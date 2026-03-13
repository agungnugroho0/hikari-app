        <?php
        
        use Livewire\Component;
        use App\Models\Core;
        use Livewire\Attributes\Reactive;
        use Livewire\WithoutUrlPagination;
        use Livewire\WithPagination;
        use Livewire\Attributes\Computed;
        use App\Livewire\Detailsiswa;
        use Livewire\Attributes\On;
        
        new class extends Component {
            use WithPagination;
            use WithoutUrlPagination;
        
            #[Reactive]
            public $idKelas;
        
            #[Reactive]
            public $search;
        
            public $status = 'siswa';
        
            #[Computed]
            public function Siswa()
            {
                $query = Core::query()->with(['detail', 'list_w']);
        
                if (!empty($this->search)) {
                    $query->where(function ($q) {
                        $q->where('nis', 'like', "%{$this->search}%")->orWhereHas('detail', function ($sub) {
                            $sub->where('nama_lengkap', 'like', "%{$this->search}%");
                        });
                    });
                } else {
                    if ($this->idKelas) {
                        $query->where('id_kelas', (int) $this->idKelas);
                    }
                    $query->where('status', '=', $this->status);
                }
                // $query->latest();
        
                return $query->latest()->paginate(25);
            }
        
            #[On('siswa-updated')]
            public function refreshList()
            {
                $this->resetPage();
            }
        
            public function updatedSearch()
            {
                $this->resetPage();
            }
            public function updatedIdKelas()
            {
                $this->resetPage();
            }
            public function pilihSiswa($nis)
            {
                $this->dispatch('pilih-siswa', nis: $nis)->to(Detailsiswa::class);
                $this->dispatch('pilih-siswa', nis: $nis);
            }
        };
        ?>

        <div>
            <div class="flex border-b border-gray-200 mb-4 gap-4 px-2">
                @foreach (['siswa' => 'Aktif', 'lolos' => 'Lolos', 'cuti' => 'Cuti'] as $key => $label)
                    <button wire:click="$set('status', '{{ $key }}')" @class([
                        'pb-2 text-sm font-medium transition-colors relative',
                        'text-brand border-b-2 border-brand' => $status === $key,
                        'text-gray-500 hover:text-gray-700' => $status !== $key,
                    ])>
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            @forelse ($this->siswa as $s)
                <div wire:click.stop="pilihSiswa('{{ $s->nis }}')" wire:key="siswa-{{ $s->nis }}"
                    class="p-2 hover:bg-gray-50 flex py-3 cursor-pointer">
                    <div class="w-0.5 @if ($s->list_w->isNotEmpty()) bg-red-900 @endif"></div>
                    <p class="ps-2">{{ $this->siswa->firstItem() + $loop->index }}</p>
                    <p class="pl-3">{{ $s->detail->nama_lengkap }}</p>
                    <div x-data="{ open: false }" class="ml-auto relative">

                        <button @click.stop="open = !open">
                            ---
                        </button>

                        <div x-show="open" @click.outside="open = false" x-transition
                            class="absolute right-0 mt-2 z-10 bg-white rounded shadow shadow-gray-200 w-40">
                            <ul class="p-2 text-sm">
                                <li>
                                    <div wire:click.stop="$dispatchTo('detailsiswa', 'pilih-siswa', { nis: '{{ $s->nis }}' }); $dispatchTo('detailsiswa', 'edit-siswa')"
                                        class="px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                        Edit
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            @empty
                <em>Tidak ada siswa</em>
            @endforelse
            <div class="flex-wrap">
                {{ $this->siswa->links() }}
            </div>


        </div>
