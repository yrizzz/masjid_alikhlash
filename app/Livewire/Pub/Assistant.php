<?php

namespace App\Livewire\Pub;

use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Kajian;
use App\Models\Program;
use App\Models\Transaction;
use App\Services\HijriService;
use App\Services\PrayerTimeService;
use Livewire\Component;

/**
 * Asisten informasi masjid. Menjawab dari data internal (jadwal, kajian,
 * donasi, keuangan, FAQ) tanpa memanggil layanan luar — jawabannya selalu
 * mencerminkan isi database masjid saat itu juga.
 */
class Assistant extends Component
{
    public string $question = '';

    /** @var array<int, array{role: string, text: string, links: array}> */
    public array $chat = [];

    public const SUGGESTIONS = [
        'Jam berapa sholat Maghrib hari ini?',
        'Ada kajian apa minggu ini?',
        'Bagaimana cara berdonasi?',
        'Berapa saldo kas masjid?',
        'Di mana lokasi masjid?',
        'Bagaimana mendaftar TPQ?',
    ];

    public function mount(): void
    {
        $this->chat[] = [
            'role' => 'bot',
            'text' => 'Assalamu\'alaikum. Saya asisten '.setting('name', config('masjid.name')).'. '
                     .'Tanyakan jadwal sholat, kajian, donasi, program, atau lokasi masjid.',
            'links' => [],
        ];
    }

    public function ask(?string $preset = null): void
    {
        $q = trim($preset ?? $this->question);
        if ($q === '') {
            return;
        }

        $this->chat[] = ['role' => 'user', 'text' => $q, 'links' => []];
        $this->chat[] = $this->answer($q);
        $this->question = '';
    }

    protected function answer(string $q): array
    {
        $t = strtolower($q);
        $has = fn (array $words) => (bool) collect($words)->first(fn ($w) => str_contains($t, $w));

        if ($has(['sholat', 'salat', 'adzan', 'azan', 'subuh', 'dzuhur', 'zuhur', 'ashar', 'maghrib', 'isya', 'imsak'])) {
            $status = app(PrayerTimeService::class)->status();
            $lines = collect($status['all'])
                ->map(fn ($time, $key) => (PrayerTimeService::PRAYERS[$key] ?? $key).' '.$time->format('H:i'))
                ->implode(' · ');

            return [
                'role' => 'bot',
                'text' => "Jadwal sholat hari ini ({$lines}). Waktu berikutnya {$status['next_label']} pukul "
                         .$status['next_time']->format('H:i').' WIB.',
                'links' => [['Jadwal lengkap', route('jadwal')], ['Arah kiblat', route('kiblat')]],
            ];
        }

        if ($has(['kajian', 'ustadz', 'pengajian', 'majelis'])) {
            $items = Kajian::published()->upcoming()->take(3)->get();

            return [
                'role' => 'bot',
                'text' => $items->isEmpty()
                    ? 'Belum ada kajian terjadwal dalam waktu dekat. Silakan pantau halaman kajian.'
                    : 'Kajian terdekat: '.$items->map(fn ($k) => $k->title.' bersama '.$k->ustadz.' ('.$k->start_at?->translatedFormat('l, d M H:i').')')->implode('; ').'.',
                'links' => [['Semua kajian', route('kajian')], ['Kalender', route('kalender')]],
            ];
        }

        if ($has(['donasi', 'infaq', 'sedekah', 'sumbang', 'wakaf'])) {
            $c = Campaign::active()->orderByDesc('is_featured')->first();

            return [
                'role' => 'bot',
                'text' => $c
                    ? "Saat ini ada campaign \"{$c->title}\" dengan target ".rupiah($c->target).' dan sudah terkumpul '.rupiah($c->collected)." ({$c->progress}%). Donasi bisa lewat transfer, QRIS, atau e-wallet."
                    : 'Donasi dapat disalurkan melalui halaman donasi — tersedia transfer bank, QRIS, dan e-wallet.',
                'links' => [['Donasi sekarang', route('donasi')], ['Transparansi keuangan', route('transparansi')]],
            ];
        }

        if ($has(['keuangan', 'saldo', 'kas', 'transparan', 'laporan', 'pemasukan', 'pengeluaran'])) {
            $in  = (float) Transaction::approved()->income()->whereYear('date', now()->year)->sum('amount');
            $out = (float) Transaction::approved()->expense()->whereYear('date', now()->year)->sum('amount');

            return [
                'role' => 'bot',
                'text' => 'Tahun '.now()->year.' tercatat pemasukan '.rupiah($in).' dan pengeluaran '.rupiah($out)
                         .'. Selisihnya '.rupiah($in - $out).'. Seluruh rincian terbuka untuk jamaah.',
                'links' => [['Lihat laporan', route('transparansi')]],
            ];
        }

        if ($has(['zakat', 'fitrah', 'nisab'])) {
            return [
                'role' => 'bot',
                'text' => 'Tersedia kalkulator zakat fitrah, maal, profesi, emas, dan perdagangan. Hasil hitungnya bisa langsung disalurkan ke masjid.',
                'links' => [['Kalkulator zakat', route('zakat')]],
            ];
        }

        if ($has(['qurban', 'kurban', 'sapi', 'kambing'])) {
            return ['role' => 'bot', 'text' => 'Pendaftaran qurban dibuka setiap tahun dengan pilihan sapi (7 slot) dan kambing. Progres penyembelihan serta distribusi dilaporkan lengkap dengan foto.', 'links' => [['Program qurban', route('qurban')]]];
        }

        if ($has(['tpq', 'ngaji anak', 'santri', 'iqra', 'tahfidz'])) {
            return ['role' => 'bot', 'text' => 'TPQ Al-Ikhlash menerima santri baru sepanjang tahun. Ada kelas Iqra hingga Al-Quran dengan absensi, nilai, dan rapor digital.', 'links' => [['Program TPQ', route('program')], ['Hubungi pengurus', route('kontak')]]];
        }

        if ($has(['lokasi', 'alamat', 'peta', 'maps', 'di mana', 'dimana'])) {
            return ['role' => 'bot', 'text' => setting('name', config('masjid.name')).' beralamat di '.setting('address', config('masjid.address')).'.', 'links' => [['Buka Google Maps', config('masjid.maps_url')], ['Kontak', route('kontak')]]];
        }

        if ($has(['booking', 'sewa', 'aula', 'pinjam', 'ruangan'])) {
            return ['role' => 'bot', 'text' => 'Ruangan masjid (aula, ruang rapat, ruang TPQ) dapat dipesan online, dan inventaris seperti kursi atau sound system bisa dipinjam dengan persetujuan takmir.', 'links' => [['Booking ruangan', route('booking')], ['Peminjaman aset', route('pinjam')]]];
        }

        if ($has(['volunteer', 'relawan', 'panitia', 'bantu'])) {
            return ['role' => 'bot', 'text' => 'Jamaah dapat mendaftar sebagai relawan sesuai minat dan keahlian — dari kebersihan, dokumentasi, hingga pengajar TPQ.', 'links' => [['Daftar volunteer', route('volunteer')]]];
        }

        if ($has(['umkm', 'usaha', 'jualan', 'produk', 'dagang'])) {
            return ['role' => 'bot', 'text' => 'Marketplace UMKM jamaah memuat usaha warga sekitar masjid lengkap dengan produk, lokasi, dan kontak WhatsApp.', 'links' => [['Jelajahi UMKM', route('umkm')]]];
        }

        if ($has(['hijriah', 'hijriyah', 'tanggal', 'ramadhan', 'idul'])) {
            $h = app(HijriService::class);

            return ['role' => 'bot', 'text' => 'Hari ini '.tanggal_id().' bertepatan dengan '.$h->format().'.', 'links' => [['Kalender hijriah', route('kalender')]]];
        }

        if ($has(['program', 'kegiatan'])) {
            $items = Program::active()->take(4)->pluck('title');

            return ['role' => 'bot', 'text' => $items->isEmpty() ? 'Belum ada program aktif.' : 'Program yang sedang berjalan: '.$items->implode(', ').'.', 'links' => [['Semua program', route('program')]]];
        }

        // Cari di FAQ sebagai cadangan.
        if ($faq = Faq::active()->where('question', 'like', "%{$q}%")->first()) {
            return ['role' => 'bot', 'text' => $faq->answer, 'links' => [['FAQ lengkap', route('faq')]]];
        }

        return [
            'role' => 'bot',
            'text' => 'Maaf, saya belum menemukan jawabannya. Coba tanyakan tentang jadwal sholat, kajian, donasi, zakat, program, atau lokasi masjid. '
                     .'Anda juga bisa mengirim pesan langsung ke pengurus.',
            'links' => [['Cari di website', route('search')], ['Hubungi pengurus', route('kontak')]],
        ];
    }

    public function render()
    {
        return view('livewire.pub.assistant', ['suggestions' => self::SUGGESTIONS])
            ->layout('components.layouts.public', ['title' => 'Tanya Asisten Masjid']);
    }
}
