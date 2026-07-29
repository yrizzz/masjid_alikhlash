<?php

namespace Database\Seeders;

use App\Models;
use App\Services\HijriService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->settings();
        $this->users();
        $this->categories();
        $this->pengurus();
        $this->profilKonten();
        $this->ibadah();
        $this->kajianDanArtikel();
        $this->galeri();
        $this->programDanDonasi();
        $this->keuangan();
        $this->asetDanRuangan();
        $this->tpq();
        $this->umkm();
        $this->pelengkap();
    }

    // ── Pengaturan & profil ────────────────────────────────────────────
    protected function settings(): void
    {
        $settings = [
            'name'       => "Masjid Jami' Al-Ikhlash",
            'tagline'    => 'Pusat Ibadah, Ilmu, dan Pemberdayaan Umat Kelurahan Kerten',
            'address'    => 'Jl. Mundu III RT 02 RW 10, Kelurahan Kerten, Kec. Laweyan, Kota Surakarta, Jawa Tengah 57143',
            'phone'      => '(0271) 716515',
            'email'      => 'sekretariat@masjidalikhlash.id',
            'maps_url'   => 'https://maps.app.goo.gl/Qhj78HL3nZ2iaW1BA',
            'mosque_lat' => '-7.5544755',
            'mosque_lng' => '110.7955645',
            'mosque_elevation' => '95',
            'founded'    => '1978',
            'land_area'  => '1.240',
            'capacity'   => '850',
            'legality'   => "Masjid Jami' Tingkat Kelurahan — Keputusan Lurah Kerten Nomor 19 Tahun 2025",
            'vision'     => "Menjadi Masjid Jami' yang makmur, mandiri, dan menjadi pusat pembinaan umat di Kelurahan Kerten berlandaskan Al-Quran dan As-Sunnah.",
            'mission'    => "Menyelenggarakan ibadah berjamaah lima waktu dan sholat Jumat yang tertib, khusyuk, dan tepat waktu.\n"
                           ."Menghidupkan majelis ilmu bagi seluruh lapisan usia jamaah se-Kelurahan Kerten.\n"
                           ."Menjadi rujukan pembinaan kemasjidan bagi mushola dan langgar di wilayah Kelurahan Kerten.\n"
                           ."Mengelola keuangan masjid secara amanah, tercatat, dan terbuka bagi jamaah.\n"
                           ."Memberdayakan ekonomi jamaah melalui pembinaan UMKM dan program sosial.\n"
                           .'Mendidik generasi Qurani melalui TPQ dan pembinaan remaja masjid.',
            'history'    => "<p>Masjid Jami' Al-Ikhlash berdiri pada tahun 1978 di Jalan Mundu III RT 02 RW 10, Kelurahan Kerten, Kecamatan Laweyan, Kota Surakarta. "
                           .'Berawal dari sebuah mushola berukuran 6 × 8 meter yang dibangun secara swadaya oleh warga, tempat ibadah ini tumbuh seiring bertambahnya jamaah di kawasan Kerten.</p>'
                           .'<p>Renovasi besar pertama dilakukan pada 1995 dengan menambah serambi dan tempat wudhu terpisah untuk jamaah putri. '
                           .'Pada 2008, masjid diperluas menjadi dua lantai sehingga mampu menampung sekitar 850 jamaah, lengkap dengan aula serbaguna di lantai dasar.</p>'
                           ."<p>Pada 8 Oktober 2025, melalui Keputusan Lurah Kerten Kota Surakarta Nomor 19 Tahun 2025 tentang Penunjukan dan Penetapan Masjid Jami' Tingkat Kelurahan, "
                           ."Masjid Al-Ikhlash resmi ditetapkan sebagai <strong>Masjid Jami' Tingkat Kelurahan Kerten</strong>. Penetapan ini didahului Rekomendasi Kantor Urusan Agama "
                           .'Kecamatan Laweyan Nomor 218/KUA.11.31.03/PP.00/10/2025.</p>'
                           .'<p>Dengan status tersebut, Al-Ikhlash mengemban amanah sebagai masjid induk yang menjadi rujukan kegiatan kemasjidan, pembinaan umat, '
                           .'serta koordinasi dakwah bagi mushola dan langgar di seluruh wilayah Kelurahan Kerten.</p>',
            'virtual_tour' => '',
            'site_description' => "Website resmi Masjid Jami' Al-Ikhlash Kerten, Laweyan, Surakarta — Masjid Jami' Tingkat Kelurahan. Jadwal sholat, kajian, donasi transparan, TPQ, dan layanan jamaah.",
            'donation_presets' => '25000,50000,100000,250000,500000,1000000',
            'zakat_gold_price' => '1950000',
            'zakat_silver_price' => '17000',
            'zakat_rice_price' => '14000',
            'zakat_fitrah_kg' => '2.5',
            'wa_number'  => '6281234567890',
            'instagram'  => 'https://instagram.com/masjidalikhlash.kerten',
            'youtube'    => 'https://youtube.com/@masjidalikhlash',
            'iqomah_subuh' => '12', 'iqomah_dzuhur' => '10', 'iqomah_ashar' => '10',
            'iqomah_maghrib' => '7', 'iqomah_isya' => '10',
        ];

        foreach ($settings as $key => $value) {
            Models\Setting::updateOrCreate(['key' => $key], ['value' => $value, 'group' => 'profil']);
        }
    }

    protected function users(): void
    {
        Models\User::updateOrCreate(
            ['email' => 'admin@alikhlash.test'],
            ['name' => 'Administrator Masjid', 'password' => 'password', 'role' => 'super-admin',
             'phone' => '081234567890', 'member_no' => 'AIK-26-ADMIN1', 'is_active' => true],
        );

        $people = [
            ['H. Ahmad Munawir, S.Ag.', 'takmir@alikhlash.test', 'takmir'],
            ['Drs. Suryanto Hadi', 'sekretaris@alikhlash.test', 'sekretaris'],
            ['Hj. Siti Aminah', 'bendahara@alikhlash.test', 'bendahara'],
            ['Ustadz Abdul Karim, Lc.', 'imam@alikhlash.test', 'imam'],
            ['Bapak Sugeng Riyadi', 'muadzin@alikhlash.test', 'muadzin'],
            ['Ustadzah Nur Halimah', 'tpq@alikhlash.test', 'tpq'],
            ['Rizky Nur Fadhilah', 'editor@alikhlash.test', 'editor'],
            ['Budi Santoso', 'budi@example.test', 'jamaah'],
            ['Endang Wahyuni', 'endang@example.test', 'jamaah'],
            ['Muhammad Fauzan', 'fauzan@example.test', 'jamaah'],
            ['Dwi Lestari', 'dwi@example.test', 'jamaah'],
            ['Agus Setiawan', 'agus@example.test', 'volunteer'],
        ];

        foreach ($people as [$name, $email, $role]) {
            Models\User::updateOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => 'password', 'role' => $role,
                 'phone' => '08'.rand(1111111111, 9999999999),
                 'address' => 'Kerten, Laweyan, Surakarta',
                 'is_active' => true],
            );
        }
    }

    protected function categories(): void
    {
        $sets = [
            'artikel' => ['Kabar Masjid', 'Tausiyah', 'Fiqih Ibadah', 'Sejarah Islam', 'Keluarga Sakinah'],
            'kajian'  => ['Tafsir Al-Quran', 'Hadits Arbain', 'Fiqih Praktis', 'Akhlak & Adab', 'Kajian Muslimah', 'Kajian Remaja'],
            'galeri'  => ['Ramadhan', 'Idul Adha', 'Kegiatan Sosial', 'Pembangunan', 'TPQ'],
            'keuangan' => ['Infaq Jumat', 'Kotak Amal', 'Donasi Campaign', 'Listrik & Air', 'Kebersihan', 'Honor Pengajar', 'Perawatan Gedung', 'Konsumsi Kegiatan', 'Sosial & Santunan'],
            'inventaris' => ['Sound System', 'Elektronik', 'Perlengkapan Sholat', 'Furnitur', 'Kebersihan'],
            'umkm'    => ['Kuliner', 'Fashion Muslim', 'Jasa', 'Toko Kelontong', 'Kerajinan'],
            'ebook'   => ['Al-Quran & Tafsir', 'Hadits', 'Fiqih', 'Materi Kajian', 'Anak & Remaja'],
            'donasi'  => ['Pembangunan', 'Operasional', 'Sosial', 'Pendidikan'],
        ];

        foreach ($sets as $type => $names) {
            foreach ($names as $i => $name) {
                Models\Category::updateOrCreate(
                    ['type' => $type, 'slug' => Str::slug($name)],
                    ['name' => $name, 'order' => $i],
                );
            }
        }
    }

    protected function pengurus(): void
    {
        $rows = [
            ['H. Ahmad Munawir, S.Ag.', 'Ketua Takmir', 'Pimpinan', 1],
            ['Drs. Suryanto Hadi', 'Sekretaris', 'Pimpinan', 1],
            ['Hj. Siti Aminah', 'Bendahara', 'Pimpinan', 1],
            ['Ustadz Abdul Karim, Lc.', 'Koordinator Imam & Ibadah', 'Bidang Ibadah', 2],
            ['Bapak Sugeng Riyadi', 'Koordinator Muadzin', 'Bidang Ibadah', 2],
            ['Ustadzah Nur Halimah', 'Kepala TPQ', 'Bidang Pendidikan', 2],
            ['Rizky Nur Fadhilah', 'Ketua Remaja Masjid', 'Bidang Remaja', 2],
            ['Agus Setiawan', 'Koordinator Sarana & Kebersihan', 'Bidang Sarana', 2],
            ['Hj. Muslimah Rahayu', 'Koordinator Muslimah', 'Bidang Muslimah', 2],
            ['Bapak Warsito', 'Koordinator Keamanan & Parkir', 'Bidang Sarana', 2],
            ['Bapak Joko Purnomo', 'Koordinator Humas & Media', 'Bidang Humas', 2],
            ['Ibu Sri Wahyuni', 'Koordinator Konsumsi & Sosial', 'Bidang Sosial', 2],
        ];

        foreach ($rows as $i => [$name, $position, $division, $level]) {
            Models\Pengurus::updateOrCreate(
                ['name' => $name, 'position' => $position],
                ['division' => $division, 'level' => $level, 'order' => $i,
                 'period_start' => 2024, 'period_end' => 2029,
                 'phone' => '08'.rand(1111111111, 9999999999),
                 'user_id' => Models\User::where('name', $name)->value('id'),
                 'is_active' => true],
            );
        }
    }

    protected function profilKonten(): void
    {
        $milestones = [
            [1978, 'Pendirian Mushola Al-Ikhlash', 'Warga RW 10 Kerten bergotong royong membangun mushola berukuran 6 × 8 meter di atas tanah wakaf.', 'sprout'],
            [1986, 'Peningkatan Status Menjadi Masjid', 'Mushola diperluas dan resmi digunakan untuk sholat Jumat setelah mendapat persetujuan Kantor Urusan Agama Kecamatan Laweyan.', 'building'],
            [1995, 'Renovasi Serambi & Tempat Wudhu', 'Penambahan serambi depan serta tempat wudhu terpisah untuk jamaah putri.', 'hammer'],
            [2008, 'Pembangunan Lantai Dua', 'Masjid diperluas menjadi dua lantai dengan kapasitas 850 jamaah dan aula serbaguna di lantai dasar.', 'layers'],
            [2016, 'Pendirian TPQ Al-Ikhlash', 'TPQ resmi dibuka dengan tiga kelas dan 45 santri pada angkatan pertama.', 'graduation-cap'],
            [2023, 'Digitalisasi Manajemen Masjid', 'Pencatatan keuangan, inventaris, dan kegiatan mulai dikelola secara digital.', 'monitor'],
            [2025, "Penetapan sebagai Masjid Jami' Tingkat Kelurahan", 'Melalui Keputusan Lurah Kerten Nomor 19 Tahun 2025 tanggal 8 Oktober 2025, Masjid Al-Ikhlash ditunjuk sebagai Masjid Jami\' Tingkat Kelurahan Kerten.', 'badge-check'],
            [2026, 'Peluncuran Smart Mosque Platform', 'Website terpadu berisi jadwal sholat, kajian, donasi, dan laporan keuangan realtime.', 'sparkles'],
        ];

        foreach ($milestones as $i => [$year, $title, $desc, $icon]) {
            Models\Milestone::updateOrCreate(
                ['year' => $year, 'title' => $title],
                ['description' => $desc, 'icon' => $icon, 'order' => $i],
            );
        }

        $facilities = [
            ['Ruang Utama 2 Lantai', 'building-2'], ['Aula Serbaguna', 'door-open'],
            ['Tempat Wudhu Terpisah', 'droplets'], ['Ruang TPQ', 'graduation-cap'],
            ['Perpustakaan Mini', 'library'], ['Area Parkir Luas', 'car'],
            ['AC & Kipas Angin', 'wind'], ['Sound System', 'volume-2'],
            ['WiFi Gratis', 'wifi'], ['Layanan Pemulasaraan Jenazah', 'heart-handshake'],
            ['Toilet Bersih', 'bath'], ['CCTV 24 Jam', 'cctv'],
        ];

        foreach ($facilities as $i => [$name, $icon]) {
            Models\Facility::updateOrCreate(['name' => $name], ['icon' => $icon, 'order' => $i]);
        }

        $texts = [
            "Masjid Al-Ikhlash resmi ditetapkan sebagai Masjid Jami' Tingkat Kelurahan Kerten (SK Lurah Kerten No. 19 Tahun 2025).",
            'Kajian rutin Tafsir Al-Quran setiap Ahad ba\'da Subuh bersama Ustadz Abdul Karim, Lc.',
            'Pendaftaran TPQ Al-Ikhlash tahun ajaran baru telah dibuka — hubungi sekretariat masjid.',
            'Laporan keuangan masjid dapat diperiksa jamaah kapan saja pada menu Transparansi.',
            'Jamaah dapat memesan aula masjid untuk akad nikah dan kegiatan sosial secara online.',
        ];

        foreach ($texts as $i => $text) {
            Models\RunningText::updateOrCreate(['text' => $text], ['order' => $i, 'is_active' => true]);
        }

        Models\Banner::updateOrCreate(
            ['title' => "Masjid Jami' Al-Ikhlash"],
            ['subtitle' => 'Kerten, Laweyan, Surakarta', 'position' => 'hero', 'order' => 0, 'is_active' => true],
        );
    }

    protected function ibadah(): void
    {
        $imams   = ['Ustadz Abdul Karim, Lc.', 'H. Ahmad Munawir, S.Ag.', 'Ustadz Hanif Mustofa', 'Ustadz Zainal Arifin', 'Drs. Suryanto Hadi'];
        $muadzin = ['Bapak Sugeng Riyadi', 'Bapak Warsito', 'Agus Setiawan', 'Rizky Nur Fadhilah'];

        foreach (range(0, 6) as $day) {
            foreach (array_keys(Models\ImamSchedule::PRAYERS) as $i => $prayer) {
                Models\ImamSchedule::updateOrCreate(
                    ['day_of_week' => $day, 'prayer' => $prayer],
                    [
                        'imam'    => $imams[($day + $i) % count($imams)],
                        'muadzin' => $muadzin[($day + $i) % count($muadzin)],
                        'backup'  => $imams[($day + $i + 2) % count($imams)],
                    ],
                );
            }
        }

        $themes = [
            ['Menjaga Amanah dalam Kehidupan Bermasyarakat', 'Ustadz Abdul Karim, Lc.'],
            ['Keutamaan Sedekah di Bulan yang Penuh Berkah', 'H. Ahmad Munawir, S.Ag.'],
            ['Adab Bertetangga dalam Islam', 'Ustadz Hanif Mustofa'],
            ['Meneladani Akhlak Rasulullah ﷺ', 'Ustadz Zainal Arifin'],
            ['Pentingnya Pendidikan Anak Sejak Dini', 'Ustadz Abdul Karim, Lc.'],
            ['Sabar dan Syukur sebagai Kunci Ketenangan', 'Drs. Suryanto Hadi'],
            ['Membangun Ekonomi Umat yang Halal dan Berkah', 'Ustadz Hanif Mustofa'],
            ['Menjaga Lisan, Menjaga Persaudaraan', 'Ustadz Zainal Arifin'],
        ];

        $friday = Carbon::now()->startOfWeek()->next(Carbon::FRIDAY)->subWeeks(4);
        foreach ($themes as $i => [$theme, $khatib]) {
            Models\JumatSchedule::updateOrCreate(
                ['date' => $friday->copy()->addWeeks($i)->toDateString()],
                [
                    'theme'   => $theme,
                    'khatib'  => $khatib,
                    'imam'    => $imams[$i % count($imams)],
                    'muadzin' => $muadzin[$i % count($muadzin)],
                    'summary' => 'Khutbah membahas penerapan nilai '.Str::lower(Str::words($theme, 3, '')).' dalam keseharian jamaah Masjid Jami\' Al-Ikhlash.',
                ],
            );
        }

        $events = [
            ['Kajian Tafsir Al-Quran', 'kajian', 1, '05:15', '06:30', 'Ruang Utama'],
            ['Pengajian Ibu-Ibu Muslimah', 'pengajian', 3, '15:30', '17:00', 'Aula Serbaguna'],
            ['TPQ Kelas Iqra', 'tpq', 0, '16:00', '17:30', 'Ruang TPQ'],
            ['Rapat Koordinasi Takmir se-Kelurahan Kerten', 'rapat', 8, '19:30', '21:00', 'Aula Serbaguna'],
            ['Kerja Bakti Bersih Masjid', 'kerja-bakti', 12, '06:30', '09:00', 'Area Masjid'],
            ['Kajian Remaja Masjid', 'kajian', 5, '19:30', '21:00', 'Aula Serbaguna'],
            ['Santunan Anak Yatim', 'agenda', 18, '08:00', '11:00', 'Aula Serbaguna'],
            ['Pelatihan Pemulasaraan Jenazah', 'agenda', 22, '08:00', '15:00', 'Aula Serbaguna'],
        ];

        foreach ($events as [$title, $type, $inDays, $start, $end, $location]) {
            $date = today()->addDays($inDays);
            Models\Event::updateOrCreate(
                ['title' => $title, 'start_at' => $date->copy()->setTimeFromTimeString($start)],
                [
                    'type' => $type, 'location' => $location,
                    'end_at' => $date->copy()->setTimeFromTimeString($end),
                    'description' => 'Kegiatan rutin Masjid Jami\' Al-Ikhlash. Terbuka untuk seluruh jamaah.',
                    'is_public' => true,
                ],
            );
        }

        Models\Livestream::updateOrCreate(
            ['title' => 'Kajian Ahad Pagi — Tafsir Surah Al-Baqarah'],
            ['platform' => 'youtube', 'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
             'start_at' => today()->next(Carbon::SUNDAY)->setTime(5, 15), 'status' => 'scheduled',
             'description' => 'Siaran langsung kajian rutin Ahad pagi dari Ruang Utama Masjid Jami\' Al-Ikhlash.'],
        );
    }

    protected function kajianDanArtikel(): void
    {
        $catKajian = Models\Category::type('kajian')->pluck('id', 'slug');

        $kajians = [
            ['Tafsir Surah Al-Baqarah: Ayat Kursi dan Keutamaannya', 'Ustadz Abdul Karim, Lc.', 'tafsir-al-quran', 'video', -3, 'Setiap Ahad ba\'da Subuh'],
            ['Hadits Arbain #1: Segala Amal Bergantung pada Niat', 'H. Ahmad Munawir, S.Ag.', 'hadits-arbain', 'audio', -10, 'Setiap Senin ba\'da Maghrib'],
            ['Fiqih Thaharah: Tata Cara Wudhu yang Benar', 'Ustadz Hanif Mustofa', 'fiqih-praktis', 'pdf', -17, null],
            ['Adab Menuntut Ilmu dalam Islam', 'Ustadz Zainal Arifin', 'akhlak-adab', 'video', 2, null],
            ['Kajian Muslimah: Menjadi Ibu Pendidik Generasi Qurani', 'Ustadzah Nur Halimah', 'kajian-muslimah', 'none', 4, 'Setiap Rabu sore'],
            ['Remaja Hebat Tanpa Galau: Menjaga Diri di Era Digital', 'Rizky Nur Fadhilah', 'kajian-remaja', 'video', 6, null],
            ['Fiqih Zakat: Menghitung Zakat Maal dengan Tepat', 'Hj. Siti Aminah', 'fiqih-praktis', 'slide', 9, null],
            ['Tafsir Surah Al-Ikhlas: Kemurnian Tauhid', 'Ustadz Abdul Karim, Lc.', 'tafsir-al-quran', 'audio', 0, 'Setiap Ahad ba\'da Subuh'],
            ['Meraih Kekhusyukan dalam Sholat', 'Ustadz Hanif Mustofa', 'akhlak-adab', 'none', 13, null],
        ];

        foreach ($kajians as [$title, $ustadz, $cat, $media, $inDays, $recurrence]) {
            Models\Kajian::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title'       => $title,
                    'category_id' => $catKajian[$cat] ?? null,
                    'ustadz'      => $ustadz,
                    'excerpt'     => 'Kajian membahas '.Str::lower(Str::after($title, ': ') ?: $title).' secara ringkas dan aplikatif untuk jamaah.',
                    'description' => '<p>Kajian ini membahas tema <strong>'.e($title).'</strong> bersama '.e($ustadz).'. '
                                    .'Materi disampaikan dengan bahasa yang mudah dipahami dan dilengkapi contoh penerapan sehari-hari.</p>'
                                    .'<h2>Poin Bahasan</h2><ul><li>Dalil dari Al-Quran dan As-Sunnah</li><li>Penjelasan para ulama</li>'
                                    .'<li>Penerapan praktis dalam keseharian</li><li>Sesi tanya jawab bersama jamaah</li></ul>'
                                    .'<p>Kajian terbuka untuk umum, gratis, dan disediakan konsumsi ringan.</p>',
                    'media_type'  => $media,
                    'media_url'   => $media === 'video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : null,
                    'start_at'    => today()->addDays($inDays)->setTime($inDays % 2 ? 19 : 5, $inDays % 2 ? 30 : 15),
                    'end_at'      => today()->addDays($inDays)->setTime($inDays % 2 ? 21 : 6, 30),
                    'recurrence'  => $recurrence,
                    'open_registration' => $inDays > 0,
                    'quota'       => $inDays > 0 ? 120 : null,
                    'views'       => rand(24, 480),
                    'is_published' => true,
                ],
            );
        }

        $catArtikel = Models\Category::type('artikel')->pluck('id', 'slug');
        $author = Models\User::where('role', 'editor')->value('id');

        $articles = [
            ["Al-Ikhlash Resmi Ditetapkan sebagai Masjid Jami' Tingkat Kelurahan Kerten", 'kabar-masjid', -20, true],
            ['Renovasi Tempat Wudhu Rampung, Jamaah Kini Lebih Nyaman', 'kabar-masjid', -2, false],
            ['Lima Amalan Ringan Berpahala Besar', 'tausiyah', -6, false],
            ['Tata Cara Sholat Jamak dan Qashar bagi Musafir', 'fiqih-ibadah', -11, false],
            ['Belajar dari Perjuangan Dakwah di Masa Rasulullah ﷺ', 'sejarah-islam', -18, false],
            ['Menjaga Keharmonisan Keluarga di Tengah Kesibukan', 'keluarga-sakinah', -25, false],
            ['TPQ Al-Ikhlash Wisuda 32 Santri Angkatan Kesembilan', 'kabar-masjid', -33, false],
        ];

        foreach ($articles as [$title, $cat, $daysAgo, $featured]) {
            $body = Str::contains($title, 'Masjid Jami')
                ? "<p>Alhamdulillah, Masjid Al-Ikhlash resmi ditetapkan sebagai <strong>Masjid Jami' Tingkat Kelurahan Kerten</strong> "
                  .'melalui Keputusan Lurah Kerten Kota Surakarta Nomor 19 Tahun 2025 yang ditetapkan pada 8 Oktober 2025.</p>'
                  .'<h2>Dasar Penetapan</h2>'
                  .'<p>Penetapan ini didahului Surat Rekomendasi Kantor Urusan Agama Kecamatan Laweyan Nomor 218/KUA.11.31.03/PP.00/10/2025 '
                  .'tentang Usulan Penetapan Masjid Jami\'. Dalam keputusan tersebut disebutkan bahwa Masjid Al-Ikhlash beralamat di '
                  .'Jalan Mundu III RT 02 RW 10, Kelurahan Kerten, Kecamatan Laweyan, Kota Surakarta.</p>'
                  .'<h2>Konsekuensi dan Amanah</h2>'
                  .'<p>Status Masjid Jami\' menempatkan Al-Ikhlash sebagai masjid induk di tingkat kelurahan. Konsekuensinya, masjid mengemban '
                  .'tanggung jawab menjadi pusat kegiatan kemasjidan, rujukan pembinaan umat, serta koordinator dakwah bagi mushola dan langgar '
                  .'di seluruh wilayah Kelurahan Kerten.</p>'
                  .'<blockquote>Hanyalah yang memakmurkan masjid-masjid Allah ialah orang yang beriman kepada Allah dan hari kemudian. (QS. At-Taubah: 18)</blockquote>'
                  .'<h2>Langkah Selanjutnya</h2>'
                  .'<p>Pengurus takmir menyusun program penguatan layanan jamaah, mulai dari penataan jadwal imam dan khatib, penguatan TPQ, '
                  .'digitalisasi laporan keuangan, hingga pembinaan UMKM jamaah. Seluruh program dapat dipantau melalui website resmi ini.</p>'
                : '<p>'.e($title).' menjadi salah satu perhatian pengurus Masjid Jami\' Al-Ikhlash pada periode ini. '
                  .'Berikut ringkasan lengkap yang dapat menjadi bahan renungan sekaligus informasi bagi jamaah.</p>'
                  .'<h2>Latar Belakang</h2>'
                  .'<p>Kegiatan ini berangkat dari usulan jamaah yang disampaikan pada rapat bulanan takmir. '
                  .'Setelah melalui pembahasan bersama, pengurus menyepakati langkah-langkah konkret yang dapat segera dijalankan.</p>'
                  .'<h2>Pelaksanaan</h2>'
                  .'<p>Pelaksanaan melibatkan pengurus, remaja masjid, dan relawan jamaah. '
                  .'Seluruh biaya bersumber dari kas masjid dan donasi jamaah yang tercatat pada laporan keuangan terbuka.</p>'
                  .'<blockquote>Sebaik-baik manusia adalah yang paling bermanfaat bagi manusia lainnya.</blockquote>'
                  .'<h2>Harapan ke Depan</h2>'
                  .'<p>Pengurus berharap kegiatan serupa terus berlanjut dan semakin banyak jamaah yang terlibat aktif '
                  .'dalam memakmurkan Masjid Jami\' Al-Ikhlash.</p>';

            Models\Article::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title'        => $title,
                    'category_id'  => $catArtikel[$cat] ?? null,
                    'user_id'      => $author,
                    'body'         => $body,
                    'published_at' => now()->subDays(abs($daysAgo)),
                    'is_featured'  => $featured,
                    'views'        => rand(40, 620),
                ],
            );
        }
    }

    protected function galeri(): void
    {
        $cats = Models\Category::type('galeri')->pluck('id', 'slug');

        $albums = [
            ['Buka Puasa Bersama Ramadhan 1447 H', 'ramadhan', -95, 9],
            ['Penyembelihan Hewan Qurban 1446 H', 'idul-adha', -160, 8],
            ['Bakti Sosial dan Santunan Anak Yatim', 'kegiatan-sosial', -45, 6],
            ['Progres Renovasi Tempat Wudhu', 'pembangunan', -12, 6],
            ['Wisuda Santri TPQ Angkatan IX', 'tpq', -33, 8],
        ];

        foreach ($albums as [$title, $cat, $daysAgo, $photoCount]) {
            $gallery = Models\Gallery::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title'       => $title,
                    'category_id' => $cats[$cat] ?? null,
                    'description' => 'Dokumentasi kegiatan '.$title.' di Masjid Jami\' Al-Ikhlash Kerten.',
                    'taken_at'    => today()->subDays(abs($daysAgo)),
                    'is_published' => true,
                ],
            );

            if ($gallery->photos()->count() === 0) {
                foreach (range(1, $photoCount) as $n) {
                    $gallery->photos()->create(['path' => '', 'caption' => 'Dokumentasi '.$n, 'order' => $n]);
                }
            }
        }
    }

    protected function programDanDonasi(): void
    {
        $programs = [
            ['Ramadhan Berkah 1447 H', 'ramadhan', 'moon', 'Rangkaian kegiatan Ramadhan: tarawih berjamaah, kajian ba\'da subuh, buka bersama, dan i\'tikaf sepuluh malam terakhir.'],
            ['Qurban Peduli Sesama', 'qurban', 'beef', 'Penghimpunan dan penyembelihan hewan qurban serta distribusi daging kepada warga kurang mampu di Kelurahan Kerten.'],
            ['Layanan Zakat Al-Ikhlash', 'zakat', 'coins', 'Penerimaan dan penyaluran zakat fitrah maupun maal kepada delapan asnaf yang berhak menerima.'],
            ['TPQ Al-Ikhlash', 'tpq', 'graduation-cap', 'Pendidikan Al-Quran untuk anak usia 5–14 tahun dengan metode Iqra dan tahfidz juz 30.'],
            ['Remaja Masjid Al-Ikhlash', 'remaja', 'users-round', 'Wadah pembinaan remaja melalui kajian, olahraga, kegiatan sosial, dan pelatihan keterampilan.'],
            ['Bakti Sosial & Santunan', 'baksos', 'heart-handshake', 'Santunan rutin anak yatim dan dhuafa, bantuan sembako, serta layanan kesehatan gratis bagi warga sekitar.'],
        ];

        foreach ($programs as $i => [$title, $type, $icon, $excerpt]) {
            Models\Program::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title, 'type' => $type, 'icon' => $icon, 'excerpt' => $excerpt,
                    'description' => '<p>'.e($excerpt).'</p>'
                                    .'<h2>Bentuk Kegiatan</h2><ul><li>Perencanaan bersama pengurus dan relawan</li>'
                                    .'<li>Penghimpunan dana melalui campaign donasi terbuka</li>'
                                    .'<li>Pelaksanaan kegiatan dengan pendampingan takmir</li>'
                                    .'<li>Pelaporan penggunaan dana secara terbuka kepada jamaah</li></ul>'
                                    .'<p>Jamaah yang ingin terlibat dapat mendaftar sebagai relawan atau menyalurkan donasi melalui website ini.</p>',
                    'pic' => 'H. Ahmad Munawir, S.Ag.',
                    'start_date' => today()->subMonths(2), 'status' => 'active',
                    'order' => $i, 'is_featured' => $i < 3,
                ],
            );
        }

        $channels = [
            ['Bank Syariah Indonesia', 'transfer', '7112233445', "Masjid Jami' Al-Ikhlash Kerten", 'Transfer ke rekening di atas, lalu konfirmasi melalui WhatsApp pengurus.'],
            ['Bank Jateng Syariah', 'transfer', '2031004455', "Takmir Masjid Jami' Al-Ikhlash", 'Konfirmasi diperlukan agar donasi tercatat atas nama Anda.'],
            ['QRIS Masjid Al-Ikhlash', 'qris', null, "Masjid Jami' Al-Ikhlash", 'Pindai QRIS menggunakan aplikasi bank atau e-wallet apa pun.'],
            ['Dana / OVO / GoPay', 'ewallet', '081234567890', "Masjid Jami' Al-Ikhlash", 'Kirim ke nomor e-wallet di atas atas nama masjid.'],
            ['Tunai di Sekretariat', 'tunai', null, null, 'Donasi tunai dapat diserahkan langsung kepada bendahara di sekretariat masjid.'],
        ];

        foreach ($channels as $i => [$name, $type, $number, $accName, $instruction]) {
            Models\PaymentChannel::updateOrCreate(
                ['name' => $name],
                ['type' => $type, 'account_number' => $number, 'account_name' => $accName,
                 'instruction' => $instruction, 'order' => $i, 'is_active' => true],
            );
        }

        $catDonasi = Models\Category::type('donasi')->pluck('id', 'slug');

        $campaigns = [
            ['Renovasi Tempat Wudhu & Toilet Jamaah', 'pembangunan', 85_000_000, 61_250_000, 45, true,
             'Tempat wudhu lama sudah berusia lebih dari 15 tahun dan mulai bocor. Mari bersama memperbaikinya agar jamaah lebih nyaman beribadah.'],
            ['Pengadaan Karpet Sholat Lantai Dua', 'operasional', 42_000_000, 28_400_000, 60, false,
             'Karpet lantai dua sudah menipis dan berbau lembap. Dibutuhkan penggantian karpet sepanjang 180 meter persegi.'],
            ['Beasiswa Santri TPQ Kurang Mampu', 'pendidikan', 30_000_000, 21_750_000, 90, true,
             'Membantu biaya SPP dan perlengkapan belajar 25 santri TPQ dari keluarga prasejahtera selama satu tahun ajaran.'],
            ['Santunan Rutin Anak Yatim & Dhuafa', 'sosial', 60_000_000, 47_900_000, 120, false,
             'Program santunan bulanan untuk 40 anak yatim dan 20 keluarga dhuafa di lingkungan Kelurahan Kerten.'],
            ['Pengadaan Sound System Masjid', 'operasional', 55_000_000, 55_000_000, -10, false,
             'Peremajaan perangkat audio agar suara adzan dan kajian terdengar jernih hingga serambi.'],
        ];

        foreach ($campaigns as [$title, $cat, $target, $collected, $daysLeft, $featured, $excerpt]) {
            $campaign = Models\Campaign::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title' => $title, 'category_id' => $catDonasi[$cat] ?? null,
                    'excerpt' => $excerpt,
                    'description' => '<p>'.e($excerpt).'</p>'
                                    .'<h2>Rincian Kebutuhan</h2><ul><li>Material dan perlengkapan utama</li>'
                                    .'<li>Jasa tukang dan pemasangan</li><li>Biaya tak terduga maksimal 10%</li></ul>'
                                    .'<h2>Penyaluran Dana</h2>'
                                    .'<p>Seluruh dana dikelola bendahara masjid dan dilaporkan pada halaman Transparansi Keuangan. '
                                    .'Bukti pembelanjaan dapat diperiksa jamaah kapan saja di sekretariat masjid.</p>',
                    'target' => $target, 'collected' => 0,
                    'start_date' => today()->subDays(30),
                    'deadline' => today()->addDays($daysLeft),
                    'status' => $daysLeft < 0 ? 'finished' : 'active',
                    'is_featured' => $featured,
                    'views' => rand(80, 900),
                ],
            );

            if ($campaign->donations()->count() === 0) {
                $donors = ['Budi Santoso', 'Endang Wahyuni', 'Muhammad Fauzan', 'Dwi Lestari', 'Keluarga H. Marzuki',
                           'Hamba Allah', 'Ibu Sri Wahyuni', 'Bapak Joko Purnomo', 'Nur Cahyani', 'Keluarga Bapak Warsito'];
                $messages = ['Semoga menjadi amal jariyah.', 'Barakallahu fiikum.', 'Semoga lancar pembangunannya.',
                             'Sedikit dari kami, semoga bermanfaat.', null, null, 'Mohon doanya untuk keluarga kami.'];

                $remaining = $collected;
                $count = rand(8, 14);

                for ($i = 0; $i < $count && $remaining > 0; $i++) {
                    $amount = $i === $count - 1 ? $remaining : min($remaining, rand(1, 12) * 250_000);

                    Models\Donation::create([
                        'campaign_id' => $campaign->id,
                        'payment_channel_id' => Models\PaymentChannel::inRandomOrder()->value('id'),
                        'name'    => $donors[array_rand($donors)],
                        'phone'   => '08'.rand(1111111111, 9999999999),
                        'amount'  => $amount,
                        'type'    => 'infaq',
                        'message' => $messages[array_rand($messages)],
                        'is_anonymous' => rand(0, 4) === 0,
                        'status'  => 'paid',
                        'paid_at' => now()->subDays(rand(1, 29))->subHours(rand(0, 23)),
                    ]);

                    $remaining -= $amount;
                }

                $campaign->recalculate();
            }

            Models\CampaignUpdate::updateOrCreate(
                ['campaign_id' => $campaign->id, 'title' => 'Perkembangan Terkini'],
                ['body' => 'Alhamdulillah, dana yang terkumpul sudah mencapai '.$campaign->fresh()->progress.'% dari target. '
                          .'Pengurus mengucapkan terima kasih atas kepercayaan jamaah. Perkembangan berikutnya akan terus kami kabarkan.'],
            );
        }

        $year = (int) now()->year;
        foreach ([
            ['sapi', 'SAPI-A', 7, 5, 3_200_000],
            ['sapi', 'SAPI-B', 7, 7, 3_200_000],
            ['kambing', 'KMB-01', 1, 1, 3_500_000],
            ['kambing', 'KMB-02', 1, 0, 3_500_000],
            ['kambing', 'KMB-03', 1, 0, 3_500_000],
        ] as [$type, $code, $slots, $taken, $price]) {
            Models\QurbanAnimal::updateOrCreate(
                ['year' => $year, 'code' => $code],
                ['type' => $type, 'slots' => $slots, 'slots_taken' => $taken,
                 'price_per_slot' => $price, 'status' => $taken >= $slots ? 'full' : 'open',
                 'description' => ucfirst($type).' sehat, memenuhi syarat syar\'i, disediakan mitra peternak binaan masjid.'],
            );
        }
    }

    protected function keuangan(): void
    {
        $accounts = [
            ['Kas Tunai Masjid', 'kas', null, 8_500_000],
            ['BSI — Operasional', 'bank', '7112233445', 34_000_000],
            ['Bank Jateng Syariah — Pembangunan', 'bank', '2031004455', 52_000_000],
        ];

        foreach ($accounts as [$name, $type, $number, $opening]) {
            Models\FinanceAccount::updateOrCreate(
                ['name' => $name],
                ['type' => $type, 'number' => $number, 'opening_balance' => $opening, 'is_active' => true],
            );
        }

        if (Models\Transaction::count() > 0) {
            return;
        }

        $accountIds = Models\FinanceAccount::pluck('id')->all();
        $income  = Models\Category::type('keuangan')->pluck('id', 'slug');
        $expense = $income;

        for ($m = 11; $m >= 0; $m--) {
            $month = now()->subMonths($m);

            foreach (range(1, 4) as $week) {
                Models\Transaction::create([
                    'finance_account_id' => $accountIds[0],
                    'category_id' => $income['infaq-jumat'] ?? null,
                    'type' => 'in', 'amount' => rand(180, 420) * 10_000,
                    'date' => $month->copy()->startOfMonth()->addWeeks($week - 1)->next(Carbon::FRIDAY),
                    'description' => 'Infaq Jumat pekan ke-'.$week, 'status' => 'approved',
                ]);
            }

            Models\Transaction::create([
                'finance_account_id' => $accountIds[0],
                'category_id' => $income['kotak-amal'] ?? null,
                'type' => 'in', 'amount' => rand(120, 380) * 10_000,
                'date' => $month->copy()->endOfMonth(),
                'description' => 'Rekapitulasi kotak amal harian', 'status' => 'approved',
            ]);

            if ($m % 2 === 0) {
                Models\Transaction::create([
                    'finance_account_id' => $accountIds[2],
                    'category_id' => $income['donasi-campaign'] ?? null,
                    'type' => 'in', 'amount' => rand(500, 2400) * 10_000,
                    'date' => $month->copy()->day(rand(5, 25)),
                    'description' => 'Penerimaan donasi campaign online', 'status' => 'approved',
                ]);
            }

            $expenses = [
                ['listrik-air', 'Tagihan listrik & PDAM', rand(90, 160) * 10_000],
                ['kebersihan', 'Honor petugas kebersihan', 800_000],
                ['honor-pengajar', 'Honor pengajar TPQ & imam', rand(150, 260) * 10_000],
                ['konsumsi-kegiatan', 'Konsumsi kajian rutin', rand(40, 120) * 10_000],
            ];

            foreach ($expenses as [$slug, $desc, $amount]) {
                Models\Transaction::create([
                    'finance_account_id' => $accountIds[1],
                    'category_id' => $expense[$slug] ?? null,
                    'type' => 'out', 'amount' => $amount,
                    'date' => $month->copy()->day(rand(3, 27)),
                    'description' => $desc, 'status' => 'approved',
                ]);
            }

            if ($m % 3 === 0) {
                Models\Transaction::create([
                    'finance_account_id' => $accountIds[1],
                    'category_id' => $expense['perawatan-gedung'] ?? null,
                    'type' => 'out', 'amount' => rand(200, 900) * 10_000,
                    'date' => $month->copy()->day(rand(5, 25)),
                    'description' => 'Perawatan dan perbaikan sarana masjid', 'status' => 'approved',
                ]);
            }

            if ($m % 2 === 1) {
                Models\Transaction::create([
                    'finance_account_id' => $accountIds[0],
                    'category_id' => $expense['sosial-santunan'] ?? null,
                    'type' => 'out', 'amount' => rand(150, 400) * 10_000,
                    'date' => $month->copy()->day(rand(10, 28)),
                    'description' => 'Santunan anak yatim & dhuafa', 'status' => 'approved',
                ]);
            }
        }
    }

    protected function asetDanRuangan(): void
    {
        $cats = Models\Category::type('inventaris')->pluck('id', 'slug');

        $items = [
            ['INV-001', 'Karpet Sajadah Roll', 'perlengkapan-sholat', 60, 'meter', 'baik', 2019, 18_000_000, 'Ruang Utama', false],
            ['INV-002', 'Speaker Toa Menara', 'sound-system', 4, 'unit', 'baik', 2021, 12_000_000, 'Menara', false],
            ['INV-003', 'Mixer Audio 12 Channel', 'sound-system', 1, 'unit', 'baik', 2021, 4_500_000, 'Ruang Kontrol', false],
            ['INV-004', 'AC Split 2 PK', 'elektronik', 6, 'unit', 'baik', 2018, 30_000_000, 'Ruang Utama', false],
            ['INV-005', 'Kipas Angin Dinding', 'elektronik', 12, 'unit', 'rusak-ringan', 2017, 3_600_000, 'Serambi', false],
            ['INV-006', 'Mukena Jamaah', 'perlengkapan-sholat', 80, 'buah', 'baik', 2023, 6_400_000, 'Lemari Muslimah', false],
            ['INV-007', 'Al-Quran Rak Masjid', 'perlengkapan-sholat', 150, 'eksemplar', 'baik', 2022, 7_500_000, 'Rak Ruang Utama', false],
            ['INV-008', 'Kursi Plastik', 'furnitur', 120, 'unit', 'baik', 2020, 9_600_000, 'Gudang', true],
            ['INV-009', 'Meja Lipat Panjang', 'furnitur', 20, 'unit', 'baik', 2020, 5_000_000, 'Gudang', true],
            ['INV-010', 'Tenda Terpal 4×6', 'furnitur', 3, 'set', 'baik', 2022, 7_200_000, 'Gudang', true],
            ['INV-011', 'Sound System Portable', 'sound-system', 2, 'set', 'baik', 2023, 8_000_000, 'Gudang', true],
            ['INV-012', 'Genset 3000 Watt', 'elektronik', 1, 'unit', 'baik', 2021, 6_500_000, 'Gudang', true],
            ['INV-013', 'Vacuum Cleaner', 'kebersihan', 2, 'unit', 'baik', 2022, 3_000_000, 'Gudang', false],
            ['INV-014', 'Lampu LED Gantung', 'elektronik', 24, 'unit', 'baik', 2019, 4_800_000, 'Ruang Utama', false],
        ];

        foreach ($items as [$code, $name, $cat, $qty, $unit, $condition, $year, $price, $location, $lendable]) {
            Models\Inventory::updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'category_id' => $cats[$cat] ?? null, 'quantity' => $qty, 'unit' => $unit,
                 'condition' => $condition, 'purchase_date' => Carbon::create($year, rand(1, 12), rand(1, 28)),
                 'price' => $price, 'location' => $location, 'is_lendable' => $lendable],
            );
        }

        $rooms = [
            ['Aula Serbaguna', 200, 'Ruang luas di lantai dasar untuk akad nikah, resepsi sederhana, seminar, dan pengajian akbar tingkat kelurahan.',
             ['AC', 'Sound System', 'Proyektor', 'Kursi 150', 'Panggung'], 500_000],
            ['Ruang Rapat Takmir', 25, 'Ruang rapat ber-AC untuk pertemuan pengurus, RT/RW, dan koordinasi takmir se-Kelurahan Kerten.',
             ['AC', 'Whiteboard', 'WiFi', 'Meja Rapat'], 0],
            ['Ruang TPQ 1', 40, 'Ruang belajar untuk kegiatan TPQ, bimbingan belajar, dan kelas tahsin dewasa.',
             ['Kipas Angin', 'Papan Tulis', 'Meja Lipat'], 0],
            ['Serambi Masjid', 150, 'Area terbuka untuk kajian, buka puasa bersama, dan kegiatan sosial.',
             ['Kipas Angin', 'Sound System', 'Karpet'], 0],
        ];

        foreach ($rooms as [$name, $capacity, $description, $facilities, $fee]) {
            Models\Room::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'capacity' => $capacity, 'description' => $description,
                 'facilities' => $facilities, 'fee' => $fee, 'is_active' => true],
            );
        }

        if (Models\RoomBooking::count() === 0) {
            $bookings = [
                ['Aula Serbaguna', 'Keluarga Bapak Suparno', 'Akad Nikah', 6, '08:00', '11:00', 'approved'],
                ['Ruang Rapat Takmir', 'Pengurus RW 10', 'Rapat Warga', 9, '19:30', '21:00', 'approved'],
                ['Serambi Masjid', 'Remaja Masjid', 'Kajian Remaja', 5, '19:30', '21:00', 'approved'],
                ['Aula Serbaguna', 'Ibu Retno Wulandari', 'Pengajian Keluarga', 15, '15:00', '17:00', 'pending'],
            ];

            foreach ($bookings as [$roomName, $name, $purpose, $inDays, $start, $end, $status]) {
                Models\RoomBooking::create([
                    'room_id' => Models\Room::where('name', $roomName)->value('id'),
                    'name' => $name, 'phone' => '08'.rand(1111111111, 9999999999),
                    'purpose' => $purpose, 'date' => today()->addDays($inDays),
                    'start_time' => $start, 'end_time' => $end,
                    'participants' => rand(20, 150), 'status' => $status,
                ]);
            }
        }
    }

    protected function tpq(): void
    {
        $classes = [
            ['Iqra 1–2', 'Pemula', 'Ustadzah Nur Halimah', 'Senin & Rabu, 16.00–17.30', 'Ruang TPQ 1', 25_000],
            ['Iqra 3–4', 'Lanjutan', 'Ustadzah Fitri Handayani', 'Selasa & Kamis, 16.00–17.30', 'Ruang TPQ 1', 25_000],
            ['Al-Quran & Tajwid', 'Menengah', 'Ustadz Hanif Mustofa', 'Senin & Kamis, 16.00–17.30', 'Ruang TPQ 2', 30_000],
            ['Tahfidz Juz 30', 'Mahir', 'Ustadz Abdul Karim, Lc.', 'Sabtu, 08.00–10.00', 'Ruang Utama', 35_000],
        ];

        foreach ($classes as [$name, $level, $teacher, $schedule, $room, $fee]) {
            Models\TpqClass::updateOrCreate(
                ['name' => $name],
                ['level' => $level, 'teacher' => $teacher, 'schedule' => $schedule,
                 'room' => $room, 'fee' => $fee, 'is_active' => true],
            );
        }

        if (Models\TpqStudent::count() > 0) {
            return;
        }

        $names = ['Aisyah Putri', 'Muhammad Rafi', 'Zahra Alifia', 'Ahmad Dzaki', 'Naila Syakira',
                  'Fathan Alfarizi', 'Khaira Ramadhani', 'Rayhan Pratama', 'Salma Nabila', 'Ilham Maulana',
                  'Kayla Azzahra', 'Bilal Ramadhan', 'Hanifa Nur', 'Yusuf Abdillah', 'Alya Ramadhani',
                  'Faiz Abdurrahman', 'Nadia Salsabila', 'Arkan Mahendra', 'Syifa Kamila', 'Daffa Hidayat'];

        $classes = Models\TpqClass::get();

        foreach ($names as $i => $name) {
            $class = $classes[$i % $classes->count()];

            $student = Models\TpqStudent::create([
                'nis' => 'TPQ-'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'tpq_class_id' => $class->id,
                'name' => $name,
                'gender' => $i % 2 ? 'P' : 'L',
                'birth_date' => today()->subYears(rand(6, 13))->subDays(rand(0, 364)),
                'parent_name' => 'Bapak/Ibu '.Str::before($name, ' '),
                'phone' => '08'.rand(1111111111, 9999999999),
                'address' => 'Kerten, Laweyan, Surakarta',
                'joined_at' => today()->subMonths(rand(3, 30)),
                'status' => 'aktif',
            ]);

            foreach (range(1, 8) as $n) {
                $student->attendances()->create([
                    'date' => today()->subDays($n * 3),
                    'status' => ['hadir', 'hadir', 'hadir', 'hadir', 'izin', 'sakit'][rand(0, 5)],
                ]);
            }

            foreach (['Tahsin', 'Tahfidz', 'Fiqih', 'Akhlak'] as $subject) {
                $score = rand(68, 96);
                $student->grades()->create([
                    'term' => 'Ganjil', 'subject' => $subject, 'score' => $score,
                    'predicate' => match (true) { $score >= 90 => 'A', $score >= 80 => 'B', $score >= 70 => 'C', default => 'D' },
                ]);
            }

            foreach (range(0, 2) as $back) {
                $period = now()->subMonths($back);
                $student->payments()->create([
                    'period' => $period->format('Y-m'),
                    'amount' => $class->fee,
                    'status' => $back === 0 ? (rand(0, 1) ? 'lunas' : 'belum') : 'lunas',
                    'paid_at' => $back === 0 ? null : $period->copy()->day(rand(5, 20)),
                ]);
            }
        }
    }

    protected function umkm(): void
    {
        $cats = Models\Category::type('umkm')->pluck('id', 'slug');

        $businesses = [
            ['Warung Sate Pak Karto', 'Bapak Sukarto', 'kuliner', 'Sate ayam dan kambing khas Solo dengan bumbu kacang resep keluarga sejak 1992.', [
                ['Sate Ayam (10 tusuk)', 25_000, 'porsi'], ['Sate Kambing (10 tusuk)', 40_000, 'porsi'], ['Gule Kambing', 30_000, 'porsi'],
            ]],
            ['Busana Muslim Az-Zahra', 'Ibu Retno Wulandari', 'fashion-muslim', 'Gamis, hijab, dan koko keluarga dengan bahan adem dan harga terjangkau.', [
                ['Gamis Katun Premium', 185_000, 'pcs'], ['Hijab Voal Motif', 45_000, 'pcs'], ['Baju Koko Dewasa', 135_000, 'pcs'],
            ]],
            ['Jasa Servis AC Barokah', 'Bapak Slamet Riyanto', 'jasa', 'Cuci, isi freon, dan perbaikan AC rumah maupun kantor. Siap panggil area Solo Raya.', [
                ['Cuci AC 1/2–1 PK', 65_000, 'unit'], ['Isi Freon R32', 250_000, 'unit'], ['Bongkar Pasang AC', 350_000, 'unit'],
            ]],
            ['Toko Kelontong Bu Endang', 'Ibu Endang Wahyuni', 'toko-kelontong', 'Sembako lengkap, gas, air galon, dan kebutuhan harian warga Kerten.', [
                ['Beras Premium 5 kg', 72_000, 'karung'], ['Gas LPG 3 kg', 22_000, 'tabung'], ['Minyak Goreng 2 L', 36_000, 'pouch'],
            ]],
            ['Kerajinan Rajut Nusa', 'Ibu Dwi Lestari', 'kerajinan', 'Tas rajut, dompet, dan suvenir handmade untuk hadiah maupun oleh-oleh.', [
                ['Tas Rajut Medium', 145_000, 'pcs'], ['Dompet Rajut', 55_000, 'pcs'], ['Suvenir Gantungan Kunci', 12_000, 'pcs'],
            ]],
            ['Katering Dapur Ummi', 'Ibu Sri Wahyuni', 'kuliner', 'Nasi kotak, tumpeng, dan snack box untuk kajian, arisan, dan acara keluarga.', [
                ['Nasi Kotak Komplit', 28_000, 'kotak'], ['Snack Box Isi 4', 15_000, 'kotak'], ['Tumpeng Mini', 250_000, 'porsi'],
            ]],
        ];

        foreach ($businesses as $i => [$name, $owner, $cat, $description, $products]) {
            $business = Models\UmkmBusiness::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name, 'owner' => $owner, 'category_id' => $cats[$cat] ?? null,
                    'description' => $description,
                    'phone' => '08'.rand(1111111111, 9999999999),
                    'whatsapp' => '08'.rand(1111111111, 9999999999),
                    'address' => 'Jl. Mundu '.rand(1, 6).', Kerten, Laweyan, Surakarta',
                    'lat' => -7.5544755 + (rand(-30, 30) / 10000),
                    'lng' => 110.7955645 + (rand(-30, 30) / 10000),
                    'status' => 'approved', 'is_featured' => $i < 2, 'views' => rand(20, 300),
                ],
            );

            if ($business->products()->count() === 0) {
                foreach ($products as [$pname, $price, $unit]) {
                    $business->products()->create([
                        'name' => $pname, 'price' => $price, 'unit' => $unit,
                        'description' => 'Tersedia setiap hari. Pemesanan lewat WhatsApp.',
                        'is_available' => true,
                    ]);
                }
            }
        }
    }

    protected function pelengkap(): void
    {
        $faqs = [
            ['umum', "Apa maksud status Masjid Jami' Tingkat Kelurahan?", "Masjid Jami' adalah masjid induk yang ditunjuk pemerintah kelurahan sebagai pusat kegiatan kemasjidan dan pembinaan umat. Masjid Al-Ikhlash ditetapkan melalui Keputusan Lurah Kerten Nomor 19 Tahun 2025 tanggal 8 Oktober 2025."],
            ['umum', 'Di mana lokasi Masjid Al-Ikhlash?', 'Masjid Al-Ikhlash berada di Jalan Mundu III RT 02 RW 10, Kelurahan Kerten, Kecamatan Laweyan, Kota Surakarta. Lokasi persisnya dapat dibuka lewat tautan Google Maps di footer website.'],
            ['umum', 'Apakah masjid menyediakan tempat parkir?', 'Ya. Tersedia area parkir motor dan mobil di sisi utara masjid dengan petugas pada waktu sholat Jumat dan kegiatan besar.'],
            ['umum', 'Bolehkah jamaah dari luar Kerten ikut kajian?', 'Tentu. Seluruh kajian di Masjid Al-Ikhlash terbuka gratis untuk umum tanpa syarat domisili.'],
            ['donasi', 'Bagaimana cara berdonasi ke masjid?', 'Buka menu Donasi, pilih campaign yang diinginkan, isi nominal dan data diri, lalu selesaikan pembayaran lewat transfer bank, QRIS, atau e-wallet. Donasi tunai juga dapat diserahkan langsung ke bendahara.'],
            ['donasi', 'Apakah donasi saya dilaporkan?', 'Ya. Setiap donasi tercatat dan masuk ke laporan keuangan yang dapat dilihat siapa saja pada halaman Transparansi Keuangan.'],
            ['donasi', 'Bisakah donasi tanpa mencantumkan nama?', 'Bisa. Centang opsi “Sembunyikan nama saya” saat berdonasi, maka nama Anda akan ditampilkan sebagai Hamba Allah.'],
            ['kajian', 'Kapan jadwal kajian rutin?', "Kajian Tafsir setiap Ahad ba'da Subuh, Hadits Arbain setiap Senin ba'da Maghrib, dan Kajian Muslimah setiap Rabu sore. Jadwal lengkap ada di menu Kajian."],
            ['kajian', 'Apakah kajian direkam?', 'Sebagian kajian direkam dalam bentuk video maupun audio dan dapat diakses ulang lewat halaman Kajian atau E-Library.'],
            ['tpq', 'Berapa biaya TPQ Al-Ikhlash?', 'SPP TPQ berkisar Rp 25.000–35.000 per bulan tergantung kelas. Tersedia beasiswa untuk santri dari keluarga kurang mampu.'],
            ['tpq', 'Berapa usia minimal santri TPQ?', 'TPQ menerima santri mulai usia 5 tahun hingga 14 tahun, dibagi ke dalam kelas Iqra, Al-Quran, dan Tahfidz.'],
            ['layanan', 'Bagaimana cara memesan aula masjid?', 'Buka menu Layanan → Booking Ruangan, pilih ruangan dan jadwal, lalu kirim permohonan. Takmir akan meninjau dan mengonfirmasi lewat WhatsApp.'],
            ['layanan', 'Apakah inventaris masjid boleh dipinjam?', 'Sebagian inventaris seperti kursi, tenda, dan sound portable dapat dipinjam warga melalui menu Peminjaman dengan persetujuan takmir.'],
        ];

        foreach ($faqs as $i => [$group, $question, $answer]) {
            Models\Faq::updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'group' => $group, 'order' => $i, 'is_active' => true],
            );
        }

        $catEbook = Models\Category::type('ebook')->pluck('id', 'slug');

        $ebooks = [
            ['Panduan Sholat Sesuai Sunnah', 'Tim Dakwah Al-Ikhlash', 'fiqih', 'pdf', 48],
            ['Kumpulan Doa Harian Anak Muslim', 'TPQ Al-Ikhlash', 'anak-remaja', 'pdf', 32],
            ['Ringkasan Hadits Arbain An-Nawawi', 'Ustadz Abdul Karim, Lc.', 'hadits', 'pdf', 96],
            ['Materi Kajian Tafsir Juz 30', 'Ustadz Abdul Karim, Lc.', 'al-quran-tafsir', 'slide', 60],
            ['Fiqih Zakat Praktis untuk Keluarga', 'Hj. Siti Aminah', 'fiqih', 'pdf', 40],
            ['Panduan Pemulasaraan Jenazah', 'Tim Bidang Sosial', 'materi-kajian', 'pdf', 55],
        ];

        foreach ($ebooks as [$title, $author, $cat, $type, $pages]) {
            Models\Ebook::updateOrCreate(
                ['slug' => Str::slug($title)],
                ['title' => $title, 'author' => $author, 'category_id' => $catEbook[$cat] ?? null,
                 'type' => $type, 'pages' => $pages, 'downloads' => rand(10, 240),
                 'description' => 'Materi '.$title.' disusun tim dakwah Masjid Jami\' Al-Ikhlash untuk kebutuhan belajar jamaah.',
                 'is_published' => true],
            );
        }

        if (Models\Volunteer::count() === 0) {
            $volunteers = [
                ['Agus Setiawan', ['Kebersihan & Perawatan Masjid', 'Keamanan & Parkir'], 'Teknisi listrik', 'active'],
                ['Rizky Nur Fadhilah', ['Dokumentasi & Media Sosial', 'IT & Multimedia'], 'Desain grafis, videografi', 'active'],
                ['Dwi Lestari', ['Konsumsi', 'Bakti Sosial & Santunan'], 'Katering', 'active'],
                ['Muhammad Fauzan', ['Panitia Kajian & Acara'], 'MC, public speaking', 'active'],
                ['Nur Cahyani', ['Pengajar TPQ'], 'Mengajar anak-anak', 'pending'],
            ];

            foreach ($volunteers as [$name, $interests, $skills, $status]) {
                Models\Volunteer::create([
                    'user_id' => Models\User::where('name', $name)->value('id'),
                    'name' => $name, 'phone' => '08'.rand(1111111111, 9999999999),
                    'interests' => $interests, 'skills' => $skills,
                    'availability' => 'Akhir pekan & sore hari', 'status' => $status,
                    'motivation' => 'Ingin ikut memakmurkan masjid dan bermanfaat bagi jamaah.',
                ]);
            }
        }

        Models\Page::updateOrCreate(
            ['slug' => 'legalitas'],
            ['title' => 'Legalitas & Dasar Hukum',
             'body' => "<p>Masjid Al-Ikhlash resmi ditetapkan sebagai <strong>Masjid Jami' Tingkat Kelurahan Kerten</strong>, "
                      .'Kecamatan Laweyan, Kota Surakarta.</p>'
                      .'<h2>Dasar Penetapan</h2>'
                      .'<ul>'
                      ."<li>Keputusan Lurah Kerten Kota Surakarta Nomor 19 Tahun 2025 tentang Penunjukan dan Penetapan Masjid Jami' Tingkat Kelurahan, ditetapkan di Surakarta pada 8 Oktober 2025.</li>"
                      ."<li>Surat Rekomendasi Kantor Urusan Agama Kecamatan Laweyan Nomor 218/KUA.11.31.03/PP.00/10/2025 tentang Usulan Penetapan Masjid Jami'.</li>"
                      .'<li>Keputusan Direktur Jenderal Bimbingan Masyarakat Islam Nomor 802 Tahun 2014 tentang Standar Manajemen Masjid.</li>'
                      .'</ul>'
                      .'<h2>Alamat Resmi</h2>'
                      .'<p>Jalan Mundu III RT 02 RW 10, Kelurahan Kerten, Kecamatan Laweyan, Kota Surakarta, Jawa Tengah 57143.</p>'
                      .'<h2>Amanah Sebagai Masjid Jami\'</h2>'
                      .'<p>Sebagai masjid induk tingkat kelurahan, Al-Ikhlash mengemban tugas menjadi pusat kegiatan kemasjidan, '
                      .'rujukan pembinaan umat Islam, serta simpul koordinasi dakwah bagi seluruh mushola dan langgar di wilayah Kelurahan Kerten.</p>',
             'is_published' => true],
        );

        Models\Page::updateOrCreate(
            ['slug' => 'tentang-website'],
            ['title' => 'Tentang Website Ini',
             'body' => "<p>Website Masjid Jami' Al-Ikhlash dibangun sebagai pusat informasi dan layanan digital bagi jamaah. "
                      .'Seluruh data — mulai jadwal sholat, kajian, keuangan, hingga inventaris — dikelola langsung oleh pengurus masjid.</p>'
                      .'<h2>Fitur Utama</h2><ul><li>Jadwal sholat otomatis dengan countdown dan pengingat iqomah</li>'
                      .'<li>Laporan keuangan terbuka yang dapat diperiksa kapan saja</li>'
                      .'<li>Donasi online dengan pantauan progres seperti crowdfunding</li>'
                      .'<li>Al-Quran digital dengan bookmark dan catatan pribadi</li>'
                      .'<li>Marketplace UMKM jamaah dan layanan booking ruangan</li></ul>',
             'is_published' => true],
        );

        foreach (app(HijriService::class)->holidaysBetween(today(), today()->addYear()) as $h) {
            Models\Event::updateOrCreate(
                ['title' => $h['name'], 'start_at' => $h['date']->startOfDay()],
                ['type' => 'hari-besar', 'all_day' => true, 'is_public' => true,
                 'description' => $h['name'].' — bertepatan dengan '.$h['hijri'].'.'],
            );
        }
    }
}
