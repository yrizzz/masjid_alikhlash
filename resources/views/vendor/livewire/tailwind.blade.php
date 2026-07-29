@php
if (! isset($scrollTo)) {
    $scrollTo = false;
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between gap-4">
            <div class="flex justify-between flex-1 sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 rounded-lg cursor-not-allowed dark:bg-stone-800/50 dark:border-stone-800 dark:text-stone-500">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 rounded-lg cursor-not-allowed dark:bg-stone-800/50 dark:border-stone-800 dark:text-stone-500">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-stone-600 dark:text-stone-400">
                        <span>{!! __('Menampilkan') !!}</span>
                        <span class="font-semibold text-stone-900 dark:text-white">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('sampai') !!}</span>
                        <span class="font-semibold text-stone-900 dark:text-white">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('dari') !!}</span>
                        <span class="font-semibold text-stone-900 dark:text-white">{{ $paginator->total() }}</span>
                        <span>{!! __('hasil') !!}</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex items-center gap-1.5 rounded-lg">
                        {{-- Previous Page Link --}}
                        @if ($paginator->onFirstPage())
                            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                <span class="relative inline-flex items-center p-2 text-stone-300 bg-stone-100/60 border border-stone-200 rounded-lg cursor-not-allowed dark:bg-stone-800/40 dark:border-stone-800 dark:text-stone-600" aria-hidden="true">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                                </span>
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center p-2 text-stone-600 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-300 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors" aria-label="{{ __('pagination.previous') }}">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/></svg>
                            </button>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-3 py-1.5 text-sm font-medium text-stone-400 bg-transparent cursor-default dark:text-stone-500">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="relative z-10 inline-flex items-center px-3.5 py-1.5 text-sm font-bold text-white bg-amber-600 border border-amber-600 rounded-lg shadow-sm cursor-default dark:bg-amber-600 dark:border-amber-600">
                                                    {{ $page }}
                                                </span>
                                            </span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-3.5 py-1.5 text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-300 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center p-2 text-stone-600 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-300 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors" aria-label="{{ __('pagination.next') }}">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                            </button>
                        @else
                            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                <span class="relative inline-flex items-center p-2 text-stone-300 bg-stone-100/60 border border-stone-200 rounded-lg cursor-not-allowed dark:bg-stone-800/40 dark:border-stone-800 dark:text-stone-600" aria-hidden="true">
                                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/></svg>
                                </span>
                            </span>
                        @endif
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
