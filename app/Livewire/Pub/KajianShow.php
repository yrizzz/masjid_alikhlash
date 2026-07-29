<?php

namespace App\Livewire\Pub;

use App\Models\Bookmark;
use App\Models\Kajian;
use App\Models\KajianRegistration;
use Livewire\Component;

class KajianShow extends Component
{
    public Kajian $kajian;

    public string $name = '';
    public string $phone = '';
    public ?KajianRegistration $registration = null;

    public function mount(Kajian $kajian): void
    {
        abort_unless($kajian->is_published, 404);

        $this->kajian = $kajian;
        $kajian->increment('views');

        if ($user = auth()->user()) {
            $this->name  = $user->name;
            $this->phone = (string) $user->phone;
            $this->registration = KajianRegistration::where('kajian_id', $kajian->id)
                ->where('user_id', $user->id)->first();
        }
    }

    public function register(): void
    {
        $this->validate([
            'name'  => 'required|min:3',
            'phone' => 'required|min:8',
        ], [], ['name' => 'nama', 'phone' => 'nomor WhatsApp']);

        $this->registration = KajianRegistration::create([
            'kajian_id' => $this->kajian->id,
            'user_id'   => auth()->id(),
            'name'      => $this->name,
            'phone'     => $this->phone,
        ]);

        $this->dispatch('toast', message: 'Pendaftaran berhasil. Simpan kode QR Anda.', variant: 'success');
    }

    public function toggleBookmark(): void
    {
        if (! auth()->check()) {
            $this->redirect(route('login'), navigate: true);

            return;
        }

        $existing = Bookmark::where('user_id', auth()->id())
            ->where('bookmarkable_type', 'kajian')
            ->where('bookmarkable_id', $this->kajian->id)->first();

        if ($existing) {
            $existing->delete();
            $this->dispatch('toast', message: 'Bookmark dihapus.');
        } else {
            Bookmark::create([
                'user_id' => auth()->id(),
                'bookmarkable_type' => 'kajian',
                'bookmarkable_id' => $this->kajian->id,
            ]);
            $this->dispatch('toast', message: 'Disimpan ke bookmark.', variant: 'success');
        }
    }

    public function render()
    {
        $bookmarked = auth()->check() && Bookmark::where('user_id', auth()->id())
            ->where('bookmarkable_type', 'kajian')->where('bookmarkable_id', $this->kajian->id)->exists();

        return view('livewire.pub.kajian-show', [
            'related' => Kajian::published()
                ->where('id', '!=', $this->kajian->id)
                ->where(fn ($q) => $q->where('category_id', $this->kajian->category_id)->orWhere('ustadz', $this->kajian->ustadz))
                ->latest('start_at')->take(3)->get(),
            'bookmarked' => $bookmarked,
            'seats' => $this->kajian->quota
                ? max(0, $this->kajian->quota - $this->kajian->registrations()->count())
                : null,
        ])->layout('components.layouts.public', [
            'title'       => $this->kajian->title,
            'description' => $this->kajian->excerpt,
        ]);
    }
}
