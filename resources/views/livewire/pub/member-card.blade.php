<div>
    <div class="mx-auto max-w-2xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('akun') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-muted-foreground hover:text-foreground">
            <i data-lucide="arrow-left" class="size-4"></i>Kembali ke akun
        </a>

        {{-- Kartu --}}
        <div class="mt-6 overflow-hidden rounded-3xl bg-gradient-to-br from-teal-700 via-teal-800 to-emerald-950 p-6 text-white shadow-2xl sm:p-8">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <span class="grid size-10 place-items-center rounded-xl bg-white/15">
                        <i data-lucide="moon-star" class="size-5"></i>
                    </span>
                    <div>
                        <p class="text-sm font-bold leading-tight">{{ setting('name', config('masjid.name')) }}</p>
                        <p class="text-[0.7rem] text-white/60">{{ config('masjid.village') }}, {{ config('masjid.city') }}</p>
                    </div>
                </div>
                <span class="rounded-full bg-white/15 px-2.5 py-1 text-[0.65rem] font-bold uppercase tracking-wider">Kartu Jamaah</span>
            </div>

            <div class="mt-8 flex flex-wrap items-end gap-6">
                <div class="min-w-0 flex-1">
                    <p class="text-xs uppercase tracking-[0.2em] text-white/50">Nama Anggota</p>
                    <p class="mt-1 truncate text-2xl font-bold">{{ $user->name }}</p>

                    <p class="mt-4 text-xs uppercase tracking-[0.2em] text-white/50">Nomor Anggota</p>
                    <p class="font-mono text-lg font-semibold tracking-wider">{{ $user->member_no }}</p>

                    <div class="mt-4 flex flex-wrap gap-1.5">
                        @foreach ($roles as $role)
                            <span class="rounded-full bg-white/15 px-2.5 py-1 text-[0.7rem] font-semibold">{{ $role }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-3">
                    {!! qr_svg($user->member_no, 120) !!}
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between border-t border-white/15 pt-4 text-xs text-white/60">
                <span>Terdaftar {{ tanggal_id($user->created_at, false) }}</span>
                <span>Total donasi {{ rupiah_short($total) }}</span>
            </div>
        </div>

        <div class="mt-6 rounded-2xl border border-border bg-card p-5">
            <h2 class="flex items-center gap-2 font-semibold"><i data-lucide="info" class="size-4 text-primary"></i>Cara pakai</h2>
            <ul class="mt-3 space-y-2 text-sm text-muted-foreground">
                <li class="flex gap-2"><i data-lucide="dot" class="mt-0.5 size-4 shrink-0 text-primary"></i>Tunjukkan QR ini kepada panitia saat check-in kajian atau kegiatan masjid.</li>
                <li class="flex gap-2"><i data-lucide="dot" class="mt-0.5 size-4 shrink-0 text-primary"></i>Kartu juga berlaku sebagai identitas volunteer dan donatur tetap.</li>
                <li class="flex gap-2"><i data-lucide="dot" class="mt-0.5 size-4 shrink-0 text-primary"></i>Simpan halaman ini di layar utama ponsel agar mudah diakses.</li>
            </ul>
            <button onclick="window.print()" class="mt-4"><x-ui.button variant="outline" size="sm" icon="printer">Cetak Kartu</x-ui.button></button>
        </div>
    </div>
</div>
