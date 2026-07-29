<?php

/*
|--------------------------------------------------------------------------
| Shell admin — nama aplikasi & pohon navigasi
|--------------------------------------------------------------------------
| Sidebar/topbar dirender rekursif dari `menu`. Struktur mengikuti
| "Struktur Menu Admin" pada PRD Smart Mosque Platform.
|
| Kunci item:
|   label     string   teks yang tampil
|   icon      string   nama ikon lucide (item level atas)
|   route     string   named route → route()
|   href      string   url eksplisit (menang atas route)
|   badge     array    ['text' => '12', 'variant' => 'neutral|primary|success|warning|hot']
|   children  array    item bersarang
*/

return [
    'name'    => 'Al-Ikhlash',
    'tagline' => 'Smart Mosque Platform',
    'version' => '1.0.0',

    'menu' => [
        [
            'group' => 'Utama',
            'items' => [
                ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'route' => 'admin.dashboard'],
                ['label' => 'Lihat Website', 'icon' => 'globe', 'href' => '/'],
            ],
        ],

        [
            'group' => 'CMS',
            'items' => [
                [
                    'label' => 'Konten',
                    'icon'  => 'newspaper',
                    'children' => [
                        ['label' => 'Artikel',  'route' => 'admin.articles'],
                        ['label' => 'Kajian',   'route' => 'admin.kajians'],
                        ['label' => 'Galeri',   'route' => 'admin.galleries'],
                        ['label' => 'Halaman',  'route' => 'admin.pages'],
                        ['label' => 'Banner',   'route' => 'admin.banners'],
                        ['label' => 'FAQ',      'route' => 'admin.faqs'],
                    ],
                ],
                ['label' => 'E-Library', 'icon' => 'library', 'route' => 'admin.ebooks'],
                ['label' => 'Live Streaming', 'icon' => 'radio', 'route' => 'admin.livestreams'],
            ],
        ],

        [
            'group' => 'Master Data',
            'items' => [
                [
                    'label' => 'Data Umat',
                    'icon'  => 'users',
                    'children' => [
                        ['label' => 'Pengurus',  'route' => 'admin.pengurus'],
                        ['label' => 'Jamaah',    'route' => 'admin.users'],
                        ['label' => 'Volunteer', 'route' => 'admin.volunteers'],
                    ],
                ],
                ['label' => 'Kategori', 'icon' => 'tags', 'route' => 'admin.categories'],
                ['label' => 'Profil Masjid', 'icon' => 'building-2', 'route' => 'admin.profile'],
            ],
        ],

        [
            'group' => 'Ibadah',
            'items' => [
                [
                    'label' => 'Jadwal',
                    'icon'  => 'clock',
                    'children' => [
                        ['label' => 'Waktu Sholat', 'route' => 'admin.prayer'],
                        ['label' => 'Jadwal Imam',  'route' => 'admin.imam'],
                        ['label' => 'Khatib Jumat', 'route' => 'admin.jumat'],
                    ],
                ],
                ['label' => 'Kalender & Agenda', 'icon' => 'calendar-days', 'route' => 'admin.events'],
            ],
        ],

        [
            'group' => 'Program',
            'items' => [
                ['label' => 'Program Masjid', 'icon' => 'sparkles', 'route' => 'admin.programs'],
                [
                    'label' => 'Donasi',
                    'icon'  => 'hand-heart',
                    'children' => [
                        ['label' => 'Campaign',         'route' => 'admin.campaigns'],
                        ['label' => 'Transaksi Donasi', 'route' => 'admin.donations'],
                        ['label' => 'Kanal Pembayaran', 'route' => 'admin.channels'],
                    ],
                ],
                ['label' => 'Zakat', 'icon' => 'coins', 'route' => 'admin.zakat'],
                ['label' => 'Qurban', 'icon' => 'beef', 'route' => 'admin.qurban'],
                [
                    'label' => 'TPQ',
                    'icon'  => 'graduation-cap',
                    'children' => [
                        ['label' => 'Kelas',   'route' => 'admin.tpq.classes'],
                        ['label' => 'Santri',  'route' => 'admin.tpq.students'],
                        ['label' => 'Absensi', 'route' => 'admin.tpq.attendance'],
                        ['label' => 'Nilai',   'route' => 'admin.tpq.grades'],
                        ['label' => 'SPP',     'route' => 'admin.tpq.payments'],
                    ],
                ],
            ],
        ],

        [
            'group' => 'Keuangan',
            'items' => [
                [
                    'label' => 'Kas & Jurnal',
                    'icon'  => 'wallet',
                    'children' => [
                        ['label' => 'Transaksi', 'route' => 'admin.transactions'],
                        ['label' => 'Rekening',  'route' => 'admin.accounts'],
                        ['label' => 'Laporan',   'route' => 'admin.finance.report'],
                    ],
                ],
            ],
        ],

        [
            'group' => 'Aset & Layanan',
            'items' => [
                [
                    'label' => 'Inventaris',
                    'icon'  => 'package',
                    'children' => [
                        ['label' => 'Aset',       'route' => 'admin.inventories'],
                        ['label' => 'Perawatan',  'route' => 'admin.maintenances'],
                        ['label' => 'Peminjaman', 'route' => 'admin.loans'],
                    ],
                ],
                ['label' => 'Booking Ruangan', 'icon' => 'door-open', 'route' => 'admin.bookings'],
                ['label' => 'QR Check-in', 'icon' => 'scan-line', 'route' => 'admin.checkin'],
                ['label' => 'UMKM Jamaah', 'icon' => 'store', 'route' => 'admin.umkm'],
            ],
        ],

        [
            'group' => 'Sistem',
            'items' => [
                ['label' => 'Media Manager', 'icon' => 'folder-open', 'route' => 'admin.media'],
                ['label' => 'Pesan Masuk', 'icon' => 'mail', 'route' => 'admin.messages'],
                ['label' => 'Analitik', 'icon' => 'chart-line', 'route' => 'admin.analytics'],
                ['label' => 'Pengaturan', 'icon' => 'settings', 'route' => 'admin.settings'],
            ],
        ],
    ],
];
