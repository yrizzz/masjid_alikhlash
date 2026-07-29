<div>
    <x-page-hero :title="$page->title" icon="file-text" />

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        @if ($page->cover)
            <img src="{{ img_url($page->cover) }}" alt="{{ $page->title }}" class="mb-8 w-full rounded-2xl border border-border object-cover" />
        @endif

        <div class="prose-masjid text-base">
            {!! $page->body !!}
        </div>
    </div>
</div>
