<div class="mt-6 grid gap-6 lg:grid-cols-2">
    @if ($mustChangeDefaultPassword)
        <div class="rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900 lg:col-span-2">
            Password Anda masih menggunakan password default <strong>123456</strong>. Demi keamanan, silakan ganti password sebelum melanjutkan ke halaman lain.
        </div>
    @endif

    <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm lg:col-span-2">
        <h2 class="text-lg font-semibold text-neutral-900">Profil</h2>
        <p class="mt-1 text-sm text-neutral-500">Perbarui nama yang tampil pada akun yang sedang aktif.</p>

        <form wire:submit.prevent="updateName" class="mt-5 grid gap-4 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
            <div>
                <label for="nama_s" class="mb-2 block text-sm font-medium text-neutral-700">Nama lengkap</label>
                <input
                    id="nama_s"
                    type="text"
                    wire:model.defer="nama_s"
                    class="block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700"
                >
                @error('nama_s')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="updateName"
                class="rounded-lg bg-red-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800 disabled:cursor-not-allowed disabled:opacity-60"
            >
                Simpan Nama
            </button>
        </form>
    </div>

    <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-neutral-900">Foto Profil</h2>
        <p class="mt-1 text-sm text-neutral-500">Upload foto baru untuk akun yang sedang aktif.</p>

        <div class="mt-5 flex items-center gap-4">
            <img
                src="{{ $foto_s ? $foto_s->temporaryUrl() : ($currentPhoto ? Storage::url($currentPhoto) : Storage::url('foto/foto.jpeg')) }}"
                alt="Foto Profil"
                class="h-20 w-20 rounded-full object-cover ring-2 ring-neutral-200"
            >
            <div class="text-sm text-neutral-500">
                <p>{{ auth()->user()->nama_s }}</p>
                <p class="text-xs text-neutral-400">{{ auth()->user()->username }}</p>
            </div>
        </div>

        <form wire:submit.prevent="updatePhoto" class="mt-5 space-y-3">
            <div>
                <label for="foto_s" class="mb-2 block text-sm font-medium text-neutral-700">Pilih foto</label>
                <input
                    id="foto_s"
                    type="file"
                    wire:model="foto_s"
                    accept="image/png,image/jpeg,image/jpg"
                    class="block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700"
                >
                <div wire:loading wire:target="foto_s" class="mt-2 text-xs text-neutral-500">Mengunggah preview...</div>
                @error('foto_s')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="updatePhoto,foto_s"
                class="rounded-lg bg-red-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-800 disabled:cursor-not-allowed disabled:opacity-60"
            >
                Simpan Foto
            </button>
        </form>
    </div>

    <div class="rounded-xl border border-neutral-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-neutral-900">Ubah Password</h2>
        <p class="mt-1 text-sm text-neutral-500">Gunakan password baru minimal 8 karakter.</p>

        <form wire:submit.prevent="updatePassword" class="mt-5 space-y-4">
            <div>
                <label for="current_password" class="mb-2 block text-sm font-medium text-neutral-700">Password saat ini</label>
                <input
                    id="current_password"
                    type="password"
                    wire:model="current_password"
                    class="block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700"
                >
                @error('current_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="new_password" class="mb-2 block text-sm font-medium text-neutral-700">Password baru</label>
                <input
                    id="new_password"
                    type="password"
                    wire:model="new_password"
                    class="block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700"
                >
                @error('new_password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="new_password_confirmation" class="mb-2 block text-sm font-medium text-neutral-700">Konfirmasi password baru</label>
                <input
                    id="new_password_confirmation"
                    type="password"
                    wire:model="new_password_confirmation"
                    class="block w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm text-neutral-700"
                >
                @error('new_password_confirmation')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="updatePassword"
                class="rounded-lg bg-neutral-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-neutral-800 disabled:cursor-not-allowed disabled:opacity-60"
            >
                Ubah Password
            </button>
        </form>
    </div>
</div>
