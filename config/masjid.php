<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi sisi publik — Smart Mosque Platform
|--------------------------------------------------------------------------
| Nilai di sini adalah default. Sebagian besar dapat ditimpa lewat menu
| Pengaturan admin yang menyimpannya ke tabel `settings`.
*/

return [
    'name'      => "Masjid Jami' Al-Ikhlash",
    'short'     => 'Al-Ikhlash',
    'status'    => "Masjid Jami' Tingkat Kelurahan Kerten",
    'tagline'   => 'Pusat Ibadah, Ilmu, dan Pemberdayaan Umat Kelurahan Kerten',
    'address'   => 'Jl. Mundu III RT 02 RW 10, Kelurahan Kerten, Kec. Laweyan, Kota Surakarta, Jawa Tengah 57143',
    'village'   => 'Kerten, Laweyan',
    'city'      => 'Surakarta',
    /* Dasar hukum penetapan sebagai Masjid Jami' tingkat kelurahan. */
    'sk_number' => 'Keputusan Lurah Kerten Nomor 19 Tahun 2025',
    'sk_date'   => '8 Oktober 2025',
    'sk_about'  => "Penunjukan dan Penetapan Masjid Jami' Tingkat Kelurahan",
    'sk_kua'    => 'Rekomendasi KUA Kecamatan Laweyan Nomor 218/KUA.11.31.03/PP.00/10/2025',
    'sk_url'    => 'https://jdih.surakarta.go.id/dokumen-hukum/view-perpu/file-name?id=red36v849l57z3erengkxqowbayj2p',
    'lat'       => -7.5544755,
    'lng'       => 110.7955645,
    'maps_url'  => 'https://maps.app.goo.gl/Qhj78HL3nZ2iaW1BA',
    'phone'     => '',
    'email'     => 'info@masjidalikhlash.id',

    /* Navigasi utama situs publik. */
    'nav' => [
        ['label' => 'Beranda',  'route' => 'home',      'icon' => 'house'],
        ['label' => 'Profil',   'route' => 'profil',    'icon' => 'building-2'],
        ['label' => 'Ibadah',   'icon' => 'moon-star', 'children' => [
            ['label' => 'Jadwal Sholat',  'route' => 'jadwal',   'icon' => 'clock', 'desc' => 'Countdown & jadwal sebulan'],
            ['label' => 'Jadwal Imam',    'route' => 'imam',     'icon' => 'user-check', 'desc' => 'Imam, muadzin & cadangan'],
            ['label' => 'Khatib Jumat',   'route' => 'jumat',    'icon' => 'mic-vocal', 'desc' => 'Tema & khatib pekan ini'],
            ['label' => 'Arah Kiblat',    'route' => 'kiblat',   'icon' => 'compass', 'desc' => 'Kompas kiblat digital'],
            ['label' => 'Al-Quran',       'route' => 'quran',    'icon' => 'book-open-text', 'desc' => 'Baca, tandai & catat'],
        ]],
        ['label' => 'Kegiatan', 'icon' => 'calendar', 'children' => [
            ['label' => 'Kajian',        'route' => 'kajian',    'icon' => 'book-open', 'desc' => 'Video, audio & materi'],
            ['label' => 'Kalender',      'route' => 'kalender',  'icon' => 'calendar-days', 'desc' => 'Agenda & hari besar'],
            ['label' => 'Live Streaming','route' => 'live',      'icon' => 'radio', 'desc' => 'Siaran langsung masjid'],
            ['label' => 'Program',       'route' => 'program',   'icon' => 'sparkles', 'desc' => 'Ramadhan, Qurban, TPQ'],
            ['label' => 'Galeri',        'route' => 'galeri',    'icon' => 'images', 'desc' => 'Dokumentasi kegiatan'],
        ]],
        ['label' => 'Donasi',   'icon' => 'hand-heart', 'children' => [
            ['label' => 'Campaign Donasi', 'route' => 'donasi',       'icon' => 'heart-handshake', 'desc' => 'Galang dana jamaah'],
            ['label' => 'Transparansi',    'route' => 'transparansi', 'icon' => 'chart-pie', 'desc' => 'Laporan keuangan realtime'],
            ['label' => 'Kalkulator Zakat','route' => 'zakat',        'icon' => 'coins', 'desc' => 'Fitrah, maal & profesi'],
            ['label' => 'Qurban',          'route' => 'qurban',       'icon' => 'beef', 'desc' => 'Daftar & pantau progres'],
        ]],
        ['label' => 'Layanan',  'icon' => 'concierge-bell', 'children' => [
            ['label' => 'Booking Ruangan', 'route' => 'booking',   'icon' => 'door-open', 'desc' => 'Aula, TPQ & ruang rapat'],
            ['label' => 'Peminjaman Aset', 'route' => 'pinjam',    'icon' => 'package', 'desc' => 'Kursi, sound, tenda'],
            ['label' => 'Volunteer',       'route' => 'volunteer', 'icon' => 'hand-helping', 'desc' => 'Daftar jadi relawan'],
            ['label' => 'UMKM Jamaah',     'route' => 'umkm',      'icon' => 'store', 'desc' => 'Marketplace usaha jamaah'],
            ['label' => 'E-Library',       'route' => 'pustaka',   'icon' => 'library', 'desc' => 'Kitab, PDF & materi'],
        ]],
        ['label' => 'Artikel',  'route' => 'artikel',   'icon' => 'newspaper'],
    ],

    /* Bottom navigation (mobile-first). */
    'bottom_nav' => [
        ['label' => 'Beranda', 'route' => 'home',    'icon' => 'house'],
        ['label' => 'Kajian',  'route' => 'kajian',  'icon' => 'book-open'],
        ['label' => 'Donasi',  'route' => 'donasi',  'icon' => 'hand-heart'],
        ['label' => 'Jadwal',  'route' => 'jadwal',  'icon' => 'clock'],
        ['label' => 'Akun',    'route' => 'akun',    'icon' => 'user-round'],
    ],

    /* Tombol aksi cepat di hero. */
    'hero_actions' => [
        ['label' => 'Donasi',         'route' => 'donasi', 'icon' => 'hand-heart',  'primary' => true],
        ['label' => 'Kajian Hari Ini','route' => 'kajian', 'icon' => 'book-open'],
        ['label' => 'Live Streaming', 'route' => 'live',   'icon' => 'radio'],
        ['label' => 'Jadwal Imam',    'route' => 'imam',   'icon' => 'user-check'],
        ['label' => 'Arah Kiblat',    'route' => 'kiblat', 'icon' => 'compass'],
    ],
];
