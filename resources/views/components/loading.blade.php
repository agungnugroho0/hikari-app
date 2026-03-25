<div {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center bg-white/70 px-4 backdrop-blur-sm']) }}>
    <div role="status" aria-live="polite"
        class="flex min-w-56 flex-col items-center gap-4 rounded-2xl border border-neutral-200 bg-white px-8 py-7 text-center shadow-xl shadow-neutral-900/10">
        <div class="relative">
            <div class="h-14 w-14 rounded-full border-4 border-red-100"></div>
            <div class="absolute inset-0 h-14 w-14 animate-spin rounded-full border-4 border-transparent border-t-red-900 border-r-red-700"></div>
            <div class="absolute inset-[10px] rounded-full bg-red-50"></div>
        </div>

        @if (trim($slot))
            <div class="text-sm text-neutral-700">
                {{ $slot }}
            </div>
        @else
            <div>
                <p class="text-sm font-semibold text-neutral-900">Memuat data</p>
                <p class="mt-1 text-xs text-neutral-500">Mohon tunggu sebentar...</p>
            </div>
        @endif

        <span class="sr-only">Loading...</span>
    </div>
</div>
