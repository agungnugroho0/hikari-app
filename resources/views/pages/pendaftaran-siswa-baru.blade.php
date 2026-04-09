<div class="min-h-screen bg-[radial-gradient(circle_at_top,#fff1f2,transparent_35%),linear-gradient(180deg,#fffdf8_0%,#fff7ed_100%)] px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                {{-- <p class="text-xs font-semibold uppercase tracking-[0.28em] text-red-900/60">Pendaftaran Publik</p> --}}
                <h1 class="mt-2 text-3xl font-semibold text-neutral-900">Form Siswa Baru</h1>
                {{-- <p class="mt-2 max-w-2xl text-sm text-neutral-600">
                    Isi data siswa baru di bawah ini. Data akan langsung masuk ke sistem dan status siswa dibuat aktif.
                </p> --}}
            </div>

            {{-- <a href="{{ route('login') }}"
                class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-neutral-200 bg-white px-4 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50">
                Ke Login
            </a> --}}
        </div>

        @if ($submitted)
            <section class="rounded-[28px] border border-emerald-200 bg-white p-6 shadow-sm">
                <div class="rounded-3xl bg-emerald-50 p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-emerald-700">Pendaftaran Berhasil</p>
                    <h2 class="mt-2 text-2xl font-semibold text-neutral-900">Data siswa baru sudah tersimpan.</h2>
                    <p class="mt-2 text-sm text-neutral-600">
                        NIS yang dibuat untuk pendaftar ini adalah <span class="font-semibold text-neutral-900">{{ $form->submittedNis }}</span>.
                    </p>
                </div>

                {{-- <div class="mt-5 flex flex-wrap gap-3">
                    <button type="button" wire:click="daftarLagi"
                        class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-red-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                        Daftar Lagi
                    </button>
                    <a href="{{ route('login') }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-2xl border border-neutral-200 bg-white px-5 py-2 text-sm font-semibold text-neutral-700 transition hover:bg-neutral-50">
                        Selesai
                    </a>
                </div> --}}
            </section>
        @else
            <form wire:submit.prevent="simpan" class="rounded-[32px] border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
                <input type="hidden" wire:model="form.id_kelas">

                <div class="grid gap-4 md:grid-cols-2">
                    <div class="rounded-3xl bg-neutral-50 p-4 md:col-span-2">
                        {{-- <p class="text-sm font-semibold text-neutral-900">Data Dasar</p> --}}
                        <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            {{-- <div> --}}
                                {{-- <label class="pl-1 text-sm font-medium text-neutral-700">Kelas aktif</label>
                                <div class="mt-1 rounded-2xl border border-dashed border-neutral-300 bg-white px-4 py-3 text-sm text-neutral-600">
                                    Kelas akan mengikuti pengaturan admin.
                                </div> --}}
                                {{-- @error('form.id_kelas') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror --}}
                            {{-- </div> --}}

                            <div class="lg:col-span-2">
                                <label for="nama_lengkap" class="pl-1 text-sm font-medium text-neutral-700">Nama lengkap</label>
                                <input id="nama_lengkap" type="text" wire:model="form.nama_lengkap"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.nama_lengkap') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="panggilan" class="pl-1 text-sm font-medium text-neutral-700">Panggilan</label>
                                <input id="panggilan" type="text" wire:model="form.panggilan"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.panggilan') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tgl_lahir" class="pl-1 text-sm font-medium text-neutral-700">Tanggal lahir</label>
                                <input id="tgl_lahir" type="date" wire:model="form.tgl_lahir"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.tgl_lahir') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="gender" class="pl-1 text-sm font-medium text-neutral-700">Jenis kelamin</label>
                                <select id="gender" wire:model="form.gender"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                    <option value="">Pilih gender</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                @error('form.gender') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="tempat_lhr" class="pl-1 text-sm font-medium text-neutral-700">Tempat lahir</label>
                                <input id="tempat_lhr" type="text" wire:model="form.tempat_lhr"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.tempat_lhr') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="agama" class="pl-1 text-sm font-medium text-neutral-700">Agama</label>
                                <input id="agama" type="text" wire:model="form.agama"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.agama') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="pernikahan" class="pl-1 text-sm font-medium text-neutral-700">Status pernikahan</label>
                                <select id="pernikahan" wire:model="form.pernikahan"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                    <option value="single">Single</option>
                                    <option value="menikah">Menikah</option>
                                    <option value="cerai">Cerai</option>
                                </select>
                                @error('form.pernikahan') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-neutral-50 p-4 md:col-span-2">
                        <p class="text-sm font-semibold text-neutral-900">Alamat</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            <div>
                                <label for="alamat_desa" class="pl-1 text-sm font-medium text-neutral-700">Desa</label>
                                <input id="alamat_desa" type="text" wire:model="form.alamat_desa"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.alamat_desa') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="alamat_rt" class="pl-1 text-sm font-medium text-neutral-700">RT</label>
                                <input id="alamat_rt" type="number" wire:model="form.alamat_rt"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.alamat_rt') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="alamat_rw" class="pl-1 text-sm font-medium text-neutral-700">RW</label>
                                <input id="alamat_rw" type="number" wire:model="form.alamat_rw"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.alamat_rw') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="alamat_kecamatan" class="pl-1 text-sm font-medium text-neutral-700">Kecamatan</label>
                                <input id="alamat_kecamatan" type="text" wire:model="form.alamat_kecamatan"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.alamat_kecamatan') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="alamat_kabupaten" class="pl-1 text-sm font-medium text-neutral-700">Kabupaten</label>
                                <input id="alamat_kabupaten" type="text" wire:model="form.alamat_kabupaten"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.alamat_kabupaten') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="alamat_provinsi" class="pl-1 text-sm font-medium text-neutral-700">Provinsi</label>
                                <input id="alamat_provinsi" type="text" wire:model="form.alamat_provinsi"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.alamat_provinsi') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="rounded-3xl bg-neutral-50 p-4 md:col-span-2">
                        <p class="text-sm font-semibold text-neutral-900">Kontak</p>
                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="wa" class="pl-1 text-sm font-medium text-neutral-700">No WhatsApp</label>
                                <input id="wa" type="text" wire:model="form.wa"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.wa') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="wa_wali" class="pl-1 text-sm font-medium text-neutral-700">No WhatsApp wali</label>
                                <input id="wa_wali" type="text" wire:model="form.wa_wali"
                                    class="mt-1 w-full rounded-2xl border border-neutral-300 px-4 py-3 text-sm">
                                @error('form.wa_wali') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label for="foto" class="pl-1 text-sm font-medium text-neutral-700">Foto</label>
                                <input id="foto" type="file" wire:model="form.foto" accept="image/jpeg,image/png"
                                    class="mt-1 w-full rounded border border-neutral-300 px-4 py-3 text-sm file:ml-3 file:border-0 file:bg-red-900 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white">
                                <div wire:loading wire:target="form.foto" class="mt-1 pl-1 text-xs text-neutral-500">Mengunggah preview...</div>
                                @if ($form->foto)
                                    <img src="{{ $form->foto->temporaryUrl() }}" alt="Preview foto siswa" class="mt-2 h-20 w-20 rounded-2xl object-cover">
                                @endif
                                @error('form.foto') <p class="mt-1 pl-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-between gap-3 border-t border-neutral-200 pt-5">
                    

                    <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-2xl bg-red-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                        Kirim Pendaftaran
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
