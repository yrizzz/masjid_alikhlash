<?php

namespace App\Livewire\Pub;

use App\Models\QuranBookmark;
use App\Services\QuranService;
use Livewire\Component;

class Quran extends Component
{
    public int $surah = 1;
    public string $search = '';
    public bool $showTranslation = true;
    public bool $showLatin = true;
    public bool $showTafsir = false;
    public ?int $noteAyah = null;
    public string $noteText = '';

    public function mount(?int $surah = null): void
    {
        $this->surah = $surah ?: (int) (auth()->user()?->quranBookmarks()
            ->where('type', 'last_read')->latest()->value('surah') ?? 1);
    }

    public function open(int $number): void
    {
        $this->surah = $number;
        $this->saveLastRead();
    }

    protected function saveLastRead(int $ayah = 1): void
    {
        if (! auth()->check()) {
            return;
        }

        QuranBookmark::updateOrCreate(
            ['user_id' => auth()->id(), 'type' => 'last_read'],
            ['surah' => $this->surah, 'ayah' => $ayah, 'surah_name' => $this->surahName()],
        );
    }

    public function markLastRead(int $ayah): void
    {
        $this->requireLogin();
        $this->saveLastRead($ayah);
        $this->dispatch('toast', message: 'Terakhir dibaca ditandai pada ayat '.$ayah.'.', variant: 'success');
    }

    public function toggleBookmark(int $ayah): void
    {
        $this->requireLogin();

        $existing = QuranBookmark::where('user_id', auth()->id())
            ->where('type', 'bookmark')->where('surah', $this->surah)->where('ayah', $ayah)->first();

        if ($existing) {
            $existing->delete();
            $this->dispatch('toast', message: 'Bookmark dihapus.');

            return;
        }

        QuranBookmark::create([
            'user_id'    => auth()->id(),
            'type'       => 'bookmark',
            'surah'      => $this->surah,
            'ayah'       => $ayah,
            'surah_name' => $this->surahName(),
        ]);

        $this->dispatch('toast', message: 'Ayat ditandai.', variant: 'success');
    }

    public function highlight(int $ayah, string $color = 'amber'): void
    {
        $this->requireLogin();

        $existing = QuranBookmark::where('user_id', auth()->id())
            ->where('type', 'highlight')->where('surah', $this->surah)->where('ayah', $ayah)->first();

        $existing
            ? $existing->delete()
            : QuranBookmark::create([
                'user_id' => auth()->id(), 'type' => 'highlight', 'color' => $color,
                'surah' => $this->surah, 'ayah' => $ayah, 'surah_name' => $this->surahName(),
            ]);
    }

    public function openNote(int $ayah): void
    {
        $this->requireLogin();
        $this->noteAyah = $ayah;
        $this->noteText = (string) QuranBookmark::where('user_id', auth()->id())
            ->where('type', 'note')->where('surah', $this->surah)->where('ayah', $ayah)->value('note');
    }

    public function saveNote(): void
    {
        $this->requireLogin();

        QuranBookmark::updateOrCreate(
            ['user_id' => auth()->id(), 'type' => 'note', 'surah' => $this->surah, 'ayah' => $this->noteAyah],
            ['note' => $this->noteText, 'surah_name' => $this->surahName()],
        );

        $this->noteAyah = null;
        $this->dispatch('toast', message: 'Catatan tersimpan.', variant: 'success');
    }

    protected function requireLogin(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'), navigate: true);
            abort(403);
        }
    }

    protected function surahName(): string
    {
        return collect(app(QuranService::class)->surahs())
            ->firstWhere('nomor', $this->surah)['namaLatin'] ?? '';
    }

    public function render()
    {
        $service = app(QuranService::class);
        $surahs  = collect($service->surahs());

        if ($this->search !== '') {
            $surahs = $surahs->filter(fn ($s) => str_contains(strtolower($s['namaLatin'].' '.$s['arti']), strtolower($this->search)));
        }

        $marks = auth()->check()
            ? auth()->user()->quranBookmarks()->where('surah', $this->surah)->get()->groupBy('type')
            : collect();

        return view('livewire.pub.quran', [
            'surahs'   => $surahs->values(),
            'detail'   => $service->surah($this->surah),
            'tafsir'   => $this->showTafsir ? $service->tafsir($this->surah) : [],
            'marks'    => $marks,
            'lastRead' => auth()->user()?->quranBookmarks()->where('type', 'last_read')->latest()->first(),
        ])->layout('components.layouts.public', ['title' => 'Al-Quran Digital']);
    }
}
