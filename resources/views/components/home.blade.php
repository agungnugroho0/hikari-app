<div>
    <header class="border-b border-slate-300 pb-2 flex gap-2 items-center " >
        <img src="{{ $foto ? asset('storage/'.$foto) : asset('img/logo.jpg') }}" alt="" class="rounded w-10 h-10 object-cover shadow">
        <div class="grow">
            <p class="text-sm">{{ $nama }}</p>
            <p class="text-sm text-gray-600"> {{$sensei->kelas->nama_kelas}}</p>
        </div>
        <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button 
                            class="data-current:font-bold data-current:text-red-900 data-current:border-l-2 flex items-center px-2 py-1.5 text-body-subtle hover:rounded-r-base hover:text-gray-800 group cursor-pointer">
                            <span class="ms-3">Logout</span>
                        </button>
                    </form>
    </header>
    <p>Grafik siswa lolos</p>
    <x-menu-bar></x-menu-bar>
</div>