<div>
    <x-page-hero title="Asisten Masjid" icon="bot" compact
                 subtitle="Tanya apa saja seputar jadwal sholat, kajian, donasi, program, dan layanan masjid." />

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Percakapan --}}
        <div class="space-y-4">
            @foreach ($chat as $msg)
                @if ($msg['role'] === 'user')
                    <div class="flex justify-end">
                        <div class="max-w-[85%] rounded-2xl rounded-ee-md bg-primary px-4 py-3 text-sm text-primary-foreground">
                            {{ $msg['text'] }}
                        </div>
                    </div>
                @else
                    <div class="flex gap-3">
                        <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary">
                            <i data-lucide="bot" class="size-4"></i>
                        </span>
                        <div class="max-w-[85%] rounded-2xl rounded-ss-md border border-border bg-card px-4 py-3">
                            <p class="text-sm leading-relaxed">{{ $msg['text'] }}</p>
                            @if (! empty($msg['links']))
                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @foreach ($msg['links'] as [$label, $href])
                                        <a href="{{ $href }}" class="inline-flex items-center gap-1 rounded-lg bg-primary/10 px-2.5 py-1.5 text-xs font-medium text-primary hover:bg-primary/20">
                                            {{ $label }}<i data-lucide="arrow-right" class="size-3"></i>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

            <div wire:loading wire:target="ask" class="flex gap-3">
                <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary"><i data-lucide="bot" class="size-4"></i></span>
                <div class="rounded-2xl border border-border bg-card px-4 py-3">
                    <span class="flex gap-1">
                        <span class="size-1.5 animate-bounce rounded-full bg-muted-foreground"></span>
                        <span class="size-1.5 animate-bounce rounded-full bg-muted-foreground" style="animation-delay:.15s"></span>
                        <span class="size-1.5 animate-bounce rounded-full bg-muted-foreground" style="animation-delay:.3s"></span>
                    </span>
                </div>
            </div>
        </div>

        {{-- Saran pertanyaan --}}
        @if (count($chat) <= 1)
            <div class="mt-6 flex flex-wrap gap-2">
                @foreach ($suggestions as $s)
                    <button wire:click="ask('{{ addslashes($s) }}')"
                            class="rounded-full border border-border px-3.5 py-2 text-xs font-medium transition-colors hover:bg-accent">
                        {{ $s }}
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Input --}}
        <form wire:submit="ask" class="sticky bottom-24 mt-8 lg:bottom-6">
            <div class="flex gap-2 rounded-2xl border border-border bg-card p-2 shadow-lg">
                <input wire:model="question" type="text" placeholder="Tulis pertanyaan Anda…"
                       class="h-11 flex-1 bg-transparent px-3 text-sm focus:outline-none" />
                <x-ui.button type="submit" size="icon" icon="send" />
            </div>
        </form>

        <p class="mt-4 text-center text-xs text-muted-foreground">
            Jawaban disusun dari data masjid yang tersimpan di sistem ini — bukan dari layanan luar.
        </p>
    </div>
</div>
