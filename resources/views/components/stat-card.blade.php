@props([
    'title',
    'value',
    'description' => null,
])

<article class="rounded-3xl border border-neutral-200 bg-white p-4 shadow-sm">
    <p class="text-sm text-neutral-500">{{ $title }}</p>
    <p class="mt-2 text-3xl font-bold text-neutral-900">{{ $value }}</p>

    @if ($description)
        <p class="mt-1 text-xs text-neutral-500">{{ $description }}</p>
    @endif
</article>
