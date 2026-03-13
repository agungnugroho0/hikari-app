<div>
    {{-- loading time --}}
    <div class="relative mx-auto w-full h-full bg-neutral-primary/95 z-10 gap-2 flex items-center justify-center flex-col"
        wire:loading>
        <div role="status">
            <svg aria-hidden="true" class="w-8 h-8 text-neutral-tertiary animate-spin fill-red-900" viewBox="0 0 100 101"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor" />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill" />
            </svg>
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div wire:loading.remove>
        @if ($isEditing)
            <livewire:editsiswa :siswa="$siswa" :key="$siswa->nis" />
        @elseif ($siswa)
            <div class="flex items-center mb-2">
                <img src="{{ $siswa->foto ? Storage::url($siswa->foto) : asset('img/logo.png') }}" alt="foto_siswa"
                    class="rounded w-10 h-10">
                <p class=" text-sm font-normal pl-2 text-gray-500">{{ $siswa->nis }}</p>
            </div>
            {{-- harus dihapus --}}
            {{-- debug : {{ optional($siswa->list_w->first())->joblist }}; --}}


            <table class="animate-fade-in mb-2">
                <tr>
                    <td class="text-gray-600 pr-2">Nama Lengkap</td>
                    <td class="">{{ $siswa->detail->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td class="text-gray-600">カタカナ </span>
                    <td class="">{{ $siswa->detail->panggilan }}</td>
                </tr>
                <tr>
                    <td class="text-gray-600">Kelas </span>
                    <td class="">{{ $siswa->kelas->nama_kelas }}</td>
                </tr>
                <tr>
                    <td class="text-gray-600">Tanggal lahir </span>
                    <td class="">{{ $siswa->detail->tgl_lahir->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td class="text-gray-600">Umur </span>
                    <td class="">{{ $siswa->detail->tgl_lahir->age }} Tahun</td>
                </tr>
                <tr>
                    <td class="text-gray-600">Tempat lahir </span>
                    <td class="">{{ $siswa->detail->tempat_lhr }} </td>
                </tr>
                <tr>
                    <td class="text-gray-600">Alamat </span>
                    <td class="">{{ $siswa->detail->alamat }} </td>
                </tr>
                <tr>
                    <td class="text-gray-600">No Telp </span>
                    <td class="">{{ $siswa->detail->wa }} </td>
                </tr>
                <tr>
                    <td class="text-gray-600">No Wali </span>
                    <td class="">{{ $siswa->detail->wa_wali }} </td>
                </tr>
                <tr>
                    <td class="text-gray-600">Status </span>
                    <td class="">{{ $siswa->detail->pernikahan }} </td>
                </tr>
            </table>

            <div class="grid grid-cols-3 gap-3">
                {{-- <button wire.click="downloadfoto">Download Foto</button> --}}
                <a href="{{ Storage::url($siswa->foto) }}" download={{ $siswa->detail->nama_lengkap }}
                    class="rounded bg-amber-950 p-2 text-center text-white font-medium">Download Foto</a>
                <div wire:click="nafuda"
                    class="rounded bg-amber-950 p-2 text-center text-white font-medium cursor-pointer">Buat Nafuda
                </div>
                <a href="https://wa.me/{{ $siswa->detail->wa }}"
                    class="rounded bg-green-700 p-2 text-center text-white font-medium">WhatsApp</a>
            </div>
            <hr class="my-5 border-gray-300">
            <div class="flex gap-2">
                @if ($siswa->status === 'siswa')
                    <div class="bg-gray-100 px-4 py-2 rounded">
                        <h2 class=" font-semibold ">Job Yang Sedang Diikuti</h2>
                        @if (optional($siswa->list_w->first())->joblist)
                            @foreach ($siswa->list_w as $s)
                                <p>Nama Perusahaan : {{ $s->joblist['perusahaan'] }}</p>
                                <p>Jenis Pekerjaan : {{ $s->joblist['nama_job'] }}</p>
                                <p>Tanggal Mensetsu : {{ $s->joblist['tgl_wawancara'] }}</p>
                                <p>Metode Wawancara : {{ $s->joblist['metode'] }}</p>
                            @endforeach
                        @else
                            <p>kosong</p>
                        @endif
                        <hr class="my-3 border-gray-300">
                        <p class="font-thin "><i>Riwayat Job Fair</i></p>
                @endif
                @if ($siswa->status === 'lolos')
                    <div class="bg-gray-100 px-4 py-2 rounded">
                        <h2 class=" font-semibold ">Lolos pada job berikut :</h2>
                        <p>{{ $siswa->listlolos->detailso->nama_so }}</p>
                        <p>Tgl lolos : {{ $siswa->listlolos->tgl_lolos }}</p>
                        <p>Nama Job : {{ $siswa->listlolos->nama_job }}</p>
                        <p>Nama Perusahaan : {{ $siswa->listlolos->nama_perusahaan }}</p>
                    </div>
                @endif
            </div>
    </div>

</div>
@else
<div class="flex flex-col items-center justify-center h-64 text-gray-400">
    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
    <p>Pilih siswa di sebelah kiri untuk melihat detail</p>
</div>
@endif
</div>
</div>
