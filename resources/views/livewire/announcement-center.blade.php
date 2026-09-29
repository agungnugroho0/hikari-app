<div>
    @if ($showPopup)
        <div wire:click.self="close" wire:keydown.escape.window="close" class="fixed inset-0 z-100 flex items-center justify-center bg-neutral-950/50 p-4" role="presentation">
            <section role="dialog" aria-modal="true" aria-labelledby="announcement-popup-title" class="max-h-[85vh] w-full max-w-xl overflow-y-auto rounded-lg bg-white p-5 shadow-2xl sm:p-6">
                <header class="flex items-start justify-between gap-4 border-b border-neutral-200 pb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase text-amber-800">Apa yang baru</p>
                        <h2 id="announcement-popup-title" class="mt-1 text-xl font-bold text-neutral-900">Pembaruan terbaru</h2>
                    </div>
                    <button type="button" wire:click="close" aria-label="Tutup pengumuman" class="rounded border border-neutral-300 px-3 py-1.5 text-sm font-medium text-neutral-700 hover:bg-neutral-100">
                        Tutup
                    </button>
                </header>
                <div class="divide-y divide-neutral-200">
                    @foreach ($announcements as $announcement)
                        <article wire:key="announcement-{{ $announcement['id'] }}" class="py-4 first:pt-5 last:pb-1">
                            <p class="text-xs text-neutral-500">{{ $announcement['published_at'] }}</p>
                            <h3 class="mt-1 font-semibold text-neutral-900">{{ $announcement['title'] }}</h3>
                            <p class="mt-1 whitespace-pre-line text-sm text-neutral-700">{{ $announcement['content'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    @endif
</div>