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
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between items-center gap-3">
            <span>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 cursor-not-allowed rounded-lg dark:text-stone-600 dark:bg-stone-800/50 dark:border-stone-800">
                        {!! __('pagination.previous') !!}
                    </span>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        @php($previousCursor = $paginator->previousCursor() ?? $paginator->cursor())
                        <button type="button" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $previousCursor?->encode() }}" wire:click="setPage('{{ $previousCursor?->encode() }}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors">
                            {!! __('pagination.previous') !!}
                        </button>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                @endif
            </span>

            <span>
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    @if(method_exists($paginator,'getCursorName'))
                        @php($nextCursor = $paginator->nextCursor() ?? $paginator->cursor())
                        <button type="button" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $nextCursor?->encode() }}" wire:click="setPage('{{ $nextCursor?->encode() }}','{{ $paginator->getCursorName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-700 bg-white border border-stone-200 rounded-lg hover:bg-amber-50 hover:text-amber-700 dark:bg-stone-900 dark:border-stone-800 dark:text-stone-200 dark:hover:bg-stone-800 dark:hover:text-amber-400 transition-colors">
                            {!! __('pagination.next') !!}
                        </button>
                    @endif
                @else
                    <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-stone-400 bg-stone-100 border border-stone-200 cursor-not-allowed rounded-lg dark:text-stone-600 dark:bg-stone-800/50 dark:border-stone-800">
                        {!! __('pagination.next') !!}
                    </span>
                @endif
            </span>
        </nav>
    @endif
</div>
