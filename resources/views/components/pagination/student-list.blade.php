@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="space-y-2">
            <div class="text-xs leading-5 text-neutral-500">
                Showing
                <span class="font-medium text-neutral-700">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-medium text-neutral-700">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-medium text-neutral-700">{{ $paginator->total() }}</span>
                results
            </div>

            <div class="overflow-x-auto pb-1">
                <div class="inline-flex min-w-max items-center rounded-md border border-slate-700 bg-slate-800 text-sm text-white shadow-sm">
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex h-9 min-w-9 items-center justify-center border-r border-slate-700 px-3 text-slate-500">
                            &lsaquo;
                        </span>
                    @else
                        <button type="button"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            class="inline-flex h-9 min-w-9 items-center justify-center border-r border-slate-700 px-3 text-white transition hover:bg-slate-700">
                            &lsaquo;
                        </button>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="inline-flex h-9 min-w-9 items-center justify-center border-r border-slate-700 px-3 text-slate-400">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                        class="inline-flex h-9 min-w-9 items-center justify-center border-r border-slate-700 bg-slate-900 px-3 font-semibold text-white">
                                        {{ $page }}
                                    </span>
                                @else
                                    <button type="button"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                        x-on:click="{{ $scrollIntoViewJsSnippet }}"
                                        class="inline-flex h-9 min-w-9 items-center justify-center border-r border-slate-700 px-3 text-white transition hover:bg-slate-700">
                                        {{ $page }}
                                    </button>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <button type="button"
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            x-on:click="{{ $scrollIntoViewJsSnippet }}"
                            wire:loading.attr="disabled"
                            class="inline-flex h-9 min-w-9 items-center justify-center px-3 text-white transition hover:bg-slate-700">
                            &rsaquo;
                        </button>
                    @else
                        <span class="inline-flex h-9 min-w-9 items-center justify-center px-3 text-slate-500">
                            &rsaquo;
                        </span>
                    @endif
                </div>
            </div>
        </nav>
    @endif
</div>
