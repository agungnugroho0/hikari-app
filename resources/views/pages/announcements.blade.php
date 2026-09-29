<div class="space-y-6">
    <header>
        <h1 class="text-2xl font-bold text-neutral-900">Pengumuman</h1>
        <p class="mt-1 text-sm text-neutral-600">Terbitkan informasi fitur baru atau perubahan untuk user.</p>
    </header>

    @if (session('announcement-status'))
        <p role="status" class="border-l-4 border-green-600 bg-green-50 px-4 py-3 text-sm text-green-900">{{ session('announcement-status') }}</p>
    @endif

    <form wire:submit="publish" class="space-y-4 border-b border-neutral-300 pb-6">
        <div>
            <label for="announcement-title" class="mb-1 block text-sm font-medium">Judul</label>
            <input id="announcement-title" type="text" wire:model="title" maxlength="255" class="w-full rounded border border-neutral-300 bg-white px-3 py-2">
            @error('title') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="announcement-content" class="mb-1 block text-sm font-medium">Perubahan atau fitur baru</label>
            <textarea id="announcement-content" wire:model="content" rows="4" maxlength="5000" class="w-full rounded border border-neutral-300 bg-white px-3 py-2"></textarea>
            @error('content') <p class="mt-1 text-sm text-red-700">{{ $message }}</p> @enderror
        </div>
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label for="announcement-akses" class="mb-1 block text-sm font-medium">Tampilkan kepada</label>
                <select id="announcement-akses" wire:model="akses" class="rounded border border-neutral-300 bg-white px-3 py-2">
                    <option value="">Semua user</option>
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                    <option value="dev">Developer</option>
                </select>
            </div>
            <button type="submit" class="rounded bg-red-900 px-4 py-2 font-medium text-white hover:bg-red-800">Terbitkan</button>
        </div>
    </form>

    <section aria-labelledby="announcement-list-title" class="space-y-3">
        <h2 id="announcement-list-title" class="text-lg font-semibold">Riwayat pengumuman</h2>
        @forelse ($announcements as $announcement)
            <article wire:key="managed-announcement-{{ $announcement->id }}" class="flex flex-wrap items-start justify-between gap-4 border-b border-neutral-200 py-4">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="font-semibold">{{ $announcement->title }}</h3>
                        <span class="text-xs {{ $announcement->published_at ? 'text-green-800' : 'text-neutral-500' }}">{{ $announcement->published_at ? 'Terbit' : 'Draf' }}</span>
                        <span class="text-xs text-neutral-500">{{ $announcement->akses ? ucfirst($announcement->akses) : 'Semua user' }}</span>
                    </div>
                    <p class="mt-1 whitespace-pre-line text-sm text-neutral-700">{{ $announcement->content }}</p>
                    <p class="mt-1 text-xs text-neutral-500">{{ $announcement->published_at?->format('d M Y, H:i') ?? 'Belum diterbitkan' }}</p>
                </div>
                <div class="flex shrink-0 gap-2">
                    <button type="button" wire:click="togglePublication({{ $announcement->id }})" class="rounded border border-neutral-300 px-3 py-1.5 text-sm hover:bg-neutral-100">
                        {{ $announcement->published_at ? 'Tarik' : 'Terbitkan' }}
                    </button>
                    <button type="button" wire:click="delete({{ $announcement->id }})" wire:confirm="Hapus pengumuman ini?" class="rounded border border-red-300 px-3 py-1.5 text-sm text-red-800 hover:bg-red-50">
                        Hapus
                    </button>
                </div>
            </article>
        @empty
            <p class="py-4 text-sm text-neutral-600">Belum ada pengumuman.</p>
        @endforelse
    </section>
</div>