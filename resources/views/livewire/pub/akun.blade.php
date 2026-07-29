<div>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Kartu profil --}}
        <div class="overflow-hidden rounded-3xl border border-border bg-gradient-to-br from-primary/12 via-card to-card p-6 sm:p-8">
            <div class="flex flex-wrap items-center gap-5">
                <x-ui.avatar :src="$user->avatar ? img_url($user->avatar) : null" :name="$user->name" size="xl" />
                <div class="min-w-0 flex-1">
                    <h1 class="text-xl font-bold tracking-tight sm:text-2xl">{{ $user->name }}</h1>
                    <p class="text-sm text-muted-foreground">{{ $user->email }}</p>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <x-ui.badge>{{ $user->role_label }}</x-ui.badge>
                        @if ($totalDonation > 0)<x-ui.badge variant="success">Donatur</x-ui.badge>@endif
                        @if ($volunteer?->status === 'active')<x-ui.badge variant="info">Volunteer</x-ui.badge>@endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <x-ui.button :href="route('akun.kartu')" icon="id-card">Kartu Anggota</x-ui.button>
                    @if ($user->isStaff())
                        <x-ui.button :href="route('admin.dashboard')" variant="outline" icon="layout-dashboard">Dashboard</x-ui.button>
                    @endif
                </div>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach ([
                    ['Total Donasi', rupiah_short($totalDonation), 'hand-heart'],
                    ['Kajian Diikuti', $registrations->count(), 'book-open'],
                    ['Bookmark', $bookmarks->count() + $quranMarks->where('type', 'bookmark')->count(), 'bookmark'],
                    ['Booking', $bookings->count(), 'door-open'],
                ] as [$label, $value, $icon])
                    <div class="rounded-2xl bg-background/70 p-4 backdrop-blur">
                        <i data-lucide="{{ $icon }}" class="size-4 text-primary"></i>
                        <p class="mt-2 text-lg font-bold">{{ $value }}</p>
                        <p class="text-xs text-muted-foreground">{{ $label }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tab --}}
        <div class="mt-6 flex gap-1 overflow-x-auto rounded-xl bg-muted p-1">
            @foreach ([
                'ringkasan' => 'Ringkasan', 'donasi' => 'Donasi', 'kajian' => 'Kajian',
                'quran' => 'Al-Quran', 'booking' => 'Booking', 'profil' => 'Profil',
            ] as $key => $label)
                <button wire:click="$set('tab', '{{ $key }}')"
                        class="whitespace-nowrap rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $tab === $key ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div class="mt-6">
            @if ($tab === 'ringkasan')
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="rounded-2xl border border-border bg-card p-5">
                        <h2 class="font-semibold">Donasi Terakhir</h2>
                        <div class="mt-4 space-y-3">
                            @forelse ($donations->take(4) as $d)
                                <div class="flex items-center gap-3">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary"><i data-lucide="hand-heart" class="size-4"></i></span>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium">{{ $d->campaign?->title ?? 'Donasi Umum' }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $d->created_at->diffForHumans() }}</p>
                                    </div>
                                    <span class="text-sm font-semibold">{{ rupiah_short($d->amount) }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-muted-foreground">Belum ada donasi.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-2xl border border-border bg-card p-5">
                        <h2 class="font-semibold">Bookmark Kajian & Artikel</h2>
                        <div class="mt-4 space-y-3">
                            @forelse ($bookmarks->take(5) as $b)
                                <div class="flex items-center gap-3">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-muted"><i data-lucide="bookmark" class="size-4"></i></span>
                                    <p class="min-w-0 flex-1 truncate text-sm font-medium">{{ $b->bookmarkable?->title ?? '—' }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-muted-foreground">Belum ada bookmark.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            @elseif ($tab === 'donasi')
                <div class="divide-y divide-border overflow-hidden rounded-2xl border border-border bg-card">
                    @forelse ($donations as $d)
                        <div class="flex flex-wrap items-center gap-3 p-4">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium">{{ $d->campaign?->title ?? 'Donasi Umum' }}</p>
                                <p class="font-mono text-xs text-muted-foreground">{{ $d->code }} · {{ tanggal_id($d->created_at, false) }}</p>
                            </div>
                            <span class="font-semibold">{{ rupiah($d->amount) }}</span>
                            <x-ui.badge :variant="$d->status === 'paid' ? 'success' : 'warning'">
                                {{ $d->status === 'paid' ? 'Lunas' : 'Menunggu' }}
                            </x-ui.badge>
                        </div>
                    @empty
                        <p class="p-10 text-center text-sm text-muted-foreground">Belum ada riwayat donasi.</p>
                    @endforelse
                </div>

            @elseif ($tab === 'kajian')
                <div class="grid gap-4 sm:grid-cols-2">
                    @forelse ($registrations as $r)
                        <div class="rounded-2xl border border-border bg-card p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="font-medium">{{ $r->kajian?->title }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $r->kajian?->start_at?->translatedFormat('l, d M Y H:i') }}</p>
                                </div>
                                <x-ui.badge :variant="$r->checked_in_at ? 'success' : 'muted'">
                                    {{ $r->checked_in_at ? 'Hadir' : 'Terdaftar' }}
                                </x-ui.badge>
                            </div>
                            <div class="mt-4 flex items-center gap-4 rounded-xl bg-muted/50 p-3">
                                <div class="shrink-0">{!! qr_svg($r->code, 78) !!}</div>
                                <div>
                                    <p class="text-xs text-muted-foreground">Kode check-in</p>
                                    <p class="font-mono text-sm font-bold">{{ $r->code }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="sm:col-span-2"><x-empty-state icon="book-open" title="Belum ada pendaftaran kajian" /></div>
                    @endforelse
                </div>

            @elseif ($tab === 'quran')
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($quranMarks as $m)
                        <a href="{{ route('quran', $m->surah) }}" wire:navigate class="rounded-2xl border border-border bg-card p-4 hover:shadow-md">
                            <div class="flex items-center gap-2">
                                <i data-lucide="{{ ['bookmark' => 'bookmark', 'note' => 'sticky-note', 'last_read' => 'book-open-text'][$m->type] ?? 'bookmark' }}" class="size-4 text-primary"></i>
                                <span class="text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                    {{ ['bookmark' => 'Bookmark', 'note' => 'Catatan', 'last_read' => 'Terakhir Dibaca'][$m->type] ?? $m->type }}
                                </span>
                            </div>
                            <p class="mt-2 font-semibold">{{ $m->surah_name ?: 'Surah '.$m->surah }} : {{ $m->ayah }}</p>
                            @if ($m->note)<p class="mt-1 line-clamp-2 text-sm text-muted-foreground">{{ $m->note }}</p>@endif
                        </a>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3">
                            <x-empty-state icon="book-open-text" title="Belum ada tanda baca" message="Bookmark, highlight, dan catatan Al-Quran Anda akan tampil di sini." />
                        </div>
                    @endforelse
                </div>

            @elseif ($tab === 'booking')
                <div class="divide-y divide-border overflow-hidden rounded-2xl border border-border bg-card">
                    @forelse ($bookings as $b)
                        <div class="flex flex-wrap items-center gap-3 p-4">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium">{{ $b->purpose }} — {{ $b->room?->name }}</p>
                                <p class="text-xs text-muted-foreground">
                                    {{ tanggal_id($b->date, false) }} · {{ substr($b->start_time, 0, 5) }}–{{ substr($b->end_time, 0, 5) }} · {{ $b->code }}
                                </p>
                            </div>
                            <x-ui.badge :variant="['pending' => 'warning', 'approved' => 'success', 'rejected' => 'destructive', 'done' => 'muted'][$b->status] ?? 'muted'">
                                {{ ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'done' => 'Selesai'][$b->status] ?? $b->status }}
                            </x-ui.badge>
                        </div>
                    @empty
                        <p class="p-10 text-center text-sm text-muted-foreground">Belum ada pemesanan ruangan.</p>
                    @endforelse
                </div>

            @else
                <form wire:submit="saveProfile" class="max-w-xl rounded-2xl border border-border bg-card p-6">
                    <h2 class="font-semibold">Data Diri</h2>
                    <div class="mt-5 space-y-4">
                        <x-ui.input wire:model="name" label="Nama Lengkap" :error="$errors->first('name')" />
                        <x-ui.input wire:model="phone" label="Nomor WhatsApp" />
                        <x-ui.input wire:model="occupation" label="Pekerjaan" />
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium">Alamat</label>
                            <textarea wire:model="address" rows="3" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                        </div>
                    </div>
                    <x-ui.button type="submit" class="mt-5" icon="save">Simpan Perubahan</x-ui.button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-5">
                    @csrf
                    <x-ui.button type="submit" variant="outline" icon="log-out">Keluar dari Akun</x-ui.button>
                </form>
            @endif
        </div>
    </div>
</div>
