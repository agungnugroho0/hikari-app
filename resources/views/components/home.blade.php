<div>
    <header class="border-b border-slate-300 pb-2 flex gap-2 items-center " >
        <img src="{{ $foto ? asset('storage/'.$foto) : asset('img/logo.jpg') }}" alt="" class="rounded w-10 h-10 object-cover shadow">
        <div class="grow">
            <p class="text-sm">{{ $nama }}</p>
            <p class="text-sm text-gray-600"> {{$sensei->kelas->nama_kelas}}</p>
        </div>
        <div>logout</div>
    </header>
    <p>Grafik siswa lolos</p>
    <x-menu-bar></x-menu-bar>
</div>