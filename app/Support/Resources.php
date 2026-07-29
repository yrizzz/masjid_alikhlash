<?php

namespace App\Support;

use App\Models;

/**
 * Definisi seluruh modul CRUD admin.
 *
 * Satu komponen Livewire generik (App\Livewire\Admin\Resource) merender
 * tabel + form dari definisi di sini, sehingga menambah modul baru cukup
 * dengan menambah satu entri — tanpa membuat controller/komponen baru.
 *
 * Struktur:
 *   path      string   segmen URL di bawah /admin
 *   route     string   nama route (tanpa prefix "admin.")
 *   model     class    model Eloquent
 *   title     string   judul halaman
 *   icon      string   ikon lucide
 *   search    array    kolom yang dicari
 *   sort      array    [kolom, arah] default
 *   with      array    eager load
 *   columns   array    kolom tabel  → key => [label, type, ...]
 *   fields    array    field form   → key => [label, type, rules, ...]
 *
 * Tipe kolom : text, muted, badge, money, date, datetime, bool, image, avatar, progress, number
 * Tipe field : text, textarea, editor, number, money, date, datetime, time, select,
 *              multiselect, toggle, image, file, slug, color, password, hidden
 */
class Resources
{
    public static function all(): array
    {
        return [
            // ── CMS ────────────────────────────────────────────────────
            'articles' => [
                'path' => 'artikel', 'route' => 'articles', 'model' => Models\Article::class,
                'title' => 'Artikel', 'singular' => 'Artikel', 'icon' => 'newspaper',
                'search' => ['title', 'excerpt'], 'sort' => ['published_at', 'desc'], 'with' => ['category', 'author'],
                'columns' => [
                    'cover'        => ['label' => '', 'type' => 'image'],
                    'title'        => ['label' => 'Judul', 'type' => 'text', 'sub' => 'excerpt'],
                    'category.name' => ['label' => 'Kategori', 'type' => 'badge'],
                    'author.name'  => ['label' => 'Penulis', 'type' => 'muted'],
                    'views'        => ['label' => 'Dibaca', 'type' => 'number'],
                    'published_at' => ['label' => 'Terbit', 'type' => 'date'],
                ],
                'fields' => [
                    'title'        => ['label' => 'Judul', 'type' => 'text', 'rules' => 'required|min:5', 'col' => 2],
                    'slug'         => ['label' => 'Slug', 'type' => 'slug', 'from' => 'title'],
                    'category_id'  => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'artikel']],
                    'cover'        => ['label' => 'Gambar Sampul', 'type' => 'image', 'col' => 2],
                    'excerpt'      => ['label' => 'Ringkasan', 'type' => 'textarea', 'col' => 2, 'help' => 'Kosongkan untuk diisi otomatis dari isi artikel.'],
                    'body'         => ['label' => 'Isi Artikel', 'type' => 'editor', 'rules' => 'required', 'col' => 2],
                    'published_at' => ['label' => 'Tanggal Terbit', 'type' => 'datetime', 'default' => 'now'],
                    'is_featured'  => ['label' => 'Tampilkan sebagai unggulan', 'type' => 'toggle'],
                ],
            ],

            'kajians' => [
                'path' => 'kajian', 'route' => 'kajians', 'model' => Models\Kajian::class,
                'title' => 'Kajian', 'singular' => 'Kajian', 'icon' => 'book-open',
                'search' => ['title', 'ustadz'], 'sort' => ['start_at', 'desc'], 'with' => ['category'],
                'columns' => [
                    'poster'   => ['label' => '', 'type' => 'image'],
                    'title'    => ['label' => 'Judul', 'type' => 'text', 'sub' => 'ustadz'],
                    'category.name' => ['label' => 'Kategori', 'type' => 'badge'],
                    'media_type' => ['label' => 'Media', 'type' => 'badge', 'map' => ['video' => 'Video', 'audio' => 'Audio', 'pdf' => 'PDF', 'slide' => 'Slide', 'none' => '—']],
                    'start_at' => ['label' => 'Jadwal', 'type' => 'datetime'],
                    'is_published' => ['label' => 'Publik', 'type' => 'bool'],
                ],
                'fields' => [
                    'title'       => ['label' => 'Judul Kajian', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'slug'        => ['label' => 'Slug', 'type' => 'slug', 'from' => 'title'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'kajian']],
                    'ustadz'      => ['label' => 'Pemateri / Ustadz', 'type' => 'text', 'rules' => 'required'],
                    'location'    => ['label' => 'Lokasi', 'type' => 'text', 'default' => 'Masjid Al-Ikhlash'],
                    'start_at'    => ['label' => 'Mulai', 'type' => 'datetime'],
                    'end_at'      => ['label' => 'Selesai', 'type' => 'datetime'],
                    'poster'      => ['label' => 'Poster', 'type' => 'image', 'col' => 2],
                    'media_type'  => ['label' => 'Jenis Media', 'type' => 'select', 'options' => ['none' => 'Tidak ada', 'video' => 'Video (YouTube)', 'audio' => 'Rekaman Audio', 'pdf' => 'PDF Materi', 'slide' => 'Slide']],
                    'media_url'   => ['label' => 'URL Media', 'type' => 'text', 'help' => 'Tautan YouTube, MP3, atau PDF.'],
                    'excerpt'     => ['label' => 'Ringkasan', 'type' => 'textarea', 'col' => 2],
                    'description' => ['label' => 'Deskripsi Lengkap', 'type' => 'editor', 'col' => 2],
                    'recurrence'  => ['label' => 'Rutinitas', 'type' => 'text', 'help' => 'Contoh: Setiap Ahad ba\'da Subuh'],
                    'quota'       => ['label' => 'Kuota Peserta', 'type' => 'number'],
                    'open_registration' => ['label' => 'Buka pendaftaran + QR check-in', 'type' => 'toggle'],
                    'is_published'      => ['label' => 'Tampilkan di website', 'type' => 'toggle', 'default' => true],
                ],
            ],

            'galleries' => [
                'path' => 'galeri', 'route' => 'galleries', 'model' => Models\Gallery::class,
                'title' => 'Galeri', 'singular' => 'Album', 'icon' => 'images',
                'search' => ['title'], 'sort' => ['taken_at', 'desc'], 'with' => ['category'],
                'columns' => [
                    'cover'    => ['label' => '', 'type' => 'image'],
                    'title'    => ['label' => 'Album', 'type' => 'text', 'sub' => 'description'],
                    'category.name' => ['label' => 'Kategori', 'type' => 'badge'],
                    'taken_at' => ['label' => 'Tanggal', 'type' => 'date'],
                    'is_published' => ['label' => 'Publik', 'type' => 'bool'],
                ],
                'fields' => [
                    'title'       => ['label' => 'Judul Album', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'slug'        => ['label' => 'Slug', 'type' => 'slug', 'from' => 'title'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'galeri']],
                    'cover'       => ['label' => 'Sampul', 'type' => 'image', 'col' => 2],
                    'taken_at'    => ['label' => 'Tanggal Kegiatan', 'type' => 'date'],
                    'is_published' => ['label' => 'Tampilkan di website', 'type' => 'toggle', 'default' => true],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'pages' => [
                'path' => 'halaman', 'route' => 'pages', 'model' => Models\Page::class,
                'title' => 'Halaman', 'singular' => 'Halaman', 'icon' => 'file-text',
                'search' => ['title'], 'sort' => ['title', 'asc'],
                'columns' => [
                    'title' => ['label' => 'Judul', 'type' => 'text', 'sub' => 'slug'],
                    'is_published' => ['label' => 'Publik', 'type' => 'bool'],
                    'updated_at' => ['label' => 'Diperbarui', 'type' => 'date'],
                ],
                'fields' => [
                    'title' => ['label' => 'Judul', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'slug'  => ['label' => 'Slug', 'type' => 'slug', 'from' => 'title'],
                    'is_published' => ['label' => 'Terbit', 'type' => 'toggle', 'default' => true],
                    'cover' => ['label' => 'Sampul', 'type' => 'image', 'col' => 2],
                    'body'  => ['label' => 'Isi', 'type' => 'editor', 'col' => 2],
                    'meta_description' => ['label' => 'Meta Description (SEO)', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'banners' => [
                'path' => 'banner', 'route' => 'banners', 'model' => Models\Banner::class,
                'title' => 'Banner & Slider', 'singular' => 'Banner', 'icon' => 'gallery-horizontal',
                'search' => ['title'], 'sort' => ['order', 'asc'],
                'columns' => [
                    'image' => ['label' => '', 'type' => 'image'],
                    'title' => ['label' => 'Judul', 'type' => 'text', 'sub' => 'subtitle'],
                    'position' => ['label' => 'Posisi', 'type' => 'badge'],
                    'order' => ['label' => 'Urutan', 'type' => 'number'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'bool'],
                ],
                'fields' => [
                    'title'    => ['label' => 'Judul', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'subtitle' => ['label' => 'Subjudul', 'type' => 'text', 'col' => 2],
                    'image'    => ['label' => 'Gambar', 'type' => 'image', 'col' => 2],
                    'link'     => ['label' => 'Tautan', 'type' => 'text'],
                    'link_text' => ['label' => 'Teks Tombol', 'type' => 'text'],
                    'position' => ['label' => 'Posisi', 'type' => 'select', 'options' => ['hero' => 'Hero', 'sidebar' => 'Sidebar', 'popup' => 'Popup']],
                    'order'    => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
                    'is_active' => ['label' => 'Aktif', 'type' => 'toggle', 'default' => true],
                ],
            ],

            'faqs' => [
                'path' => 'faq', 'route' => 'faqs', 'model' => Models\Faq::class,
                'title' => 'FAQ', 'singular' => 'FAQ', 'icon' => 'circle-help',
                'search' => ['question'], 'sort' => ['order', 'asc'],
                'columns' => [
                    'question' => ['label' => 'Pertanyaan', 'type' => 'text', 'sub' => 'answer'],
                    'group' => ['label' => 'Grup', 'type' => 'badge'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'bool'],
                ],
                'fields' => [
                    'question' => ['label' => 'Pertanyaan', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'answer'   => ['label' => 'Jawaban', 'type' => 'textarea', 'rules' => 'required', 'col' => 2],
                    'group'    => ['label' => 'Grup', 'type' => 'select', 'options' => ['umum' => 'Umum', 'donasi' => 'Donasi', 'kajian' => 'Kajian', 'tpq' => 'TPQ', 'layanan' => 'Layanan']],
                    'order'    => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
                    'is_active' => ['label' => 'Aktif', 'type' => 'toggle', 'default' => true],
                ],
            ],

            'ebooks' => [
                'path' => 'pustaka', 'route' => 'ebooks', 'model' => Models\Ebook::class,
                'title' => 'E-Library', 'singular' => 'Pustaka', 'icon' => 'library',
                'search' => ['title', 'author'], 'sort' => ['created_at', 'desc'], 'with' => ['category'],
                'columns' => [
                    'cover' => ['label' => '', 'type' => 'image'],
                    'title' => ['label' => 'Judul', 'type' => 'text', 'sub' => 'author'],
                    'type'  => ['label' => 'Jenis', 'type' => 'badge'],
                    'downloads' => ['label' => 'Unduhan', 'type' => 'number'],
                ],
                'fields' => [
                    'title'  => ['label' => 'Judul', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'slug'   => ['label' => 'Slug', 'type' => 'slug', 'from' => 'title'],
                    'author' => ['label' => 'Penulis', 'type' => 'text'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'ebook']],
                    'type'   => ['label' => 'Jenis', 'type' => 'select', 'options' => ['pdf' => 'PDF', 'kitab' => 'Kitab', 'slide' => 'Slide', 'video' => 'Video', 'audio' => 'Audio']],
                    'cover'  => ['label' => 'Sampul', 'type' => 'image'],
                    'file'   => ['label' => 'Berkas', 'type' => 'file'],
                    'external_url' => ['label' => 'URL Eksternal', 'type' => 'text', 'col' => 2],
                    'pages'  => ['label' => 'Jumlah Halaman', 'type' => 'number'],
                    'is_published' => ['label' => 'Terbit', 'type' => 'toggle', 'default' => true],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'livestreams' => [
                'path' => 'live', 'route' => 'livestreams', 'model' => Models\Livestream::class,
                'title' => 'Live Streaming', 'singular' => 'Siaran', 'icon' => 'radio',
                'search' => ['title'], 'sort' => ['start_at', 'desc'],
                'columns' => [
                    'title' => ['label' => 'Judul', 'type' => 'text', 'sub' => 'url'],
                    'platform' => ['label' => 'Platform', 'type' => 'badge'],
                    'start_at' => ['label' => 'Mulai', 'type' => 'datetime'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['scheduled' => 'Terjadwal', 'live' => 'LIVE', 'ended' => 'Selesai']],
                ],
                'fields' => [
                    'title' => ['label' => 'Judul Siaran', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'platform' => ['label' => 'Platform', 'type' => 'select', 'options' => ['youtube' => 'YouTube', 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'tiktok' => 'TikTok']],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['scheduled' => 'Terjadwal', 'live' => 'Sedang Live', 'ended' => 'Selesai']],
                    'url' => ['label' => 'URL', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'start_at' => ['label' => 'Waktu Mulai', 'type' => 'datetime'],
                    'thumbnail' => ['label' => 'Thumbnail', 'type' => 'image'],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            // ── Master data ────────────────────────────────────────────
            'pengurus' => [
                'path' => 'pengurus', 'route' => 'pengurus', 'model' => Models\Pengurus::class,
                'title' => 'Pengurus Masjid', 'singular' => 'Pengurus', 'icon' => 'user-round-cog',
                'search' => ['name', 'position'], 'sort' => ['order', 'asc'],
                'columns' => [
                    'photo' => ['label' => '', 'type' => 'avatar'],
                    'name' => ['label' => 'Nama', 'type' => 'text', 'sub' => 'position'],
                    'division' => ['label' => 'Bidang', 'type' => 'badge'],
                    'phone' => ['label' => 'Kontak', 'type' => 'muted'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'bool'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Lengkap', 'type' => 'text', 'rules' => 'required'],
                    'position' => ['label' => 'Jabatan', 'type' => 'text', 'rules' => 'required'],
                    'division' => ['label' => 'Bidang', 'type' => 'text'],
                    'level' => ['label' => 'Level', 'type' => 'select', 'options' => [1 => 'Pimpinan Inti', 2 => 'Pengurus Harian', 3 => 'Anggota Bidang'], 'default' => 2],
                    'photo' => ['label' => 'Foto', 'type' => 'image'],
                    'phone' => ['label' => 'Telepon / WA', 'type' => 'text'],
                    'email' => ['label' => 'Email', 'type' => 'text'],
                    'period_start' => ['label' => 'Awal Periode', 'type' => 'number'],
                    'period_end' => ['label' => 'Akhir Periode', 'type' => 'number'],
                    'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
                    'is_active' => ['label' => 'Masih menjabat', 'type' => 'toggle', 'default' => true],
                    'bio' => ['label' => 'Biografi Singkat', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'users' => [
                'path' => 'jamaah', 'route' => 'users', 'model' => Models\User::class,
                'title' => 'Jamaah & Pengguna', 'singular' => 'Pengguna', 'icon' => 'users',
                'search' => ['name', 'email', 'phone', 'member_no'], 'sort' => ['created_at', 'desc'],
                'columns' => [
                    'avatar' => ['label' => '', 'type' => 'avatar'],
                    'name' => ['label' => 'Nama', 'type' => 'text', 'sub' => 'email'],
                    'member_no' => ['label' => 'No. Anggota', 'type' => 'muted'],
                    'role' => ['label' => 'Peran', 'type' => 'badge', 'map' => Models\User::ROLES],
                    'phone' => ['label' => 'Telepon', 'type' => 'muted'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'bool'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Lengkap', 'type' => 'text', 'rules' => 'required'],
                    'email' => ['label' => 'Email', 'type' => 'text', 'rules' => 'required|email'],
                    'password' => ['label' => 'Kata Sandi', 'type' => 'password', 'help' => 'Kosongkan bila tidak ingin mengubah.'],
                    'role' => ['label' => 'Peran', 'type' => 'select', 'options' => Models\User::ROLES, 'default' => 'jamaah'],
                    'member_no' => ['label' => 'No. Anggota', 'type' => 'text'],
                    'phone' => ['label' => 'Telepon / WA', 'type' => 'text'],
                    'gender' => ['label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan']],
                    'birth_date' => ['label' => 'Tanggal Lahir', 'type' => 'date'],
                    'occupation' => ['label' => 'Pekerjaan', 'type' => 'text'],
                    'avatar' => ['label' => 'Foto', 'type' => 'image'],
                    'is_active' => ['label' => 'Akun aktif', 'type' => 'toggle', 'default' => true],
                    'address' => ['label' => 'Alamat', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'volunteers' => [
                'path' => 'volunteer', 'route' => 'volunteers', 'model' => Models\Volunteer::class,
                'title' => 'Volunteer', 'singular' => 'Volunteer', 'icon' => 'hand-helping',
                'search' => ['name', 'phone'], 'sort' => ['created_at', 'desc'],
                'columns' => [
                    'name' => ['label' => 'Nama', 'type' => 'text', 'sub' => 'phone'],
                    'skills' => ['label' => 'Keahlian', 'type' => 'muted'],
                    'availability' => ['label' => 'Ketersediaan', 'type' => 'muted'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['pending' => 'Menunggu', 'active' => 'Aktif', 'inactive' => 'Nonaktif', 'rejected' => 'Ditolak']],
                    'created_at' => ['label' => 'Daftar', 'type' => 'date'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama', 'type' => 'text', 'rules' => 'required'],
                    'phone' => ['label' => 'Telepon / WA', 'type' => 'text'],
                    'email' => ['label' => 'Email', 'type' => 'text'],
                    'availability' => ['label' => 'Ketersediaan Waktu', 'type' => 'text'],
                    'skills' => ['label' => 'Keahlian', 'type' => 'text', 'col' => 2],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Menunggu', 'active' => 'Aktif', 'inactive' => 'Nonaktif', 'rejected' => 'Ditolak']],
                    'address' => ['label' => 'Alamat', 'type' => 'textarea', 'col' => 2],
                    'motivation' => ['label' => 'Motivasi', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'categories' => [
                'path' => 'kategori', 'route' => 'categories', 'model' => Models\Category::class,
                'title' => 'Kategori', 'singular' => 'Kategori', 'icon' => 'tags',
                'search' => ['name'], 'sort' => ['type', 'asc'],
                'columns' => [
                    'name' => ['label' => 'Nama', 'type' => 'text', 'sub' => 'slug'],
                    'type' => ['label' => 'Digunakan Untuk', 'type' => 'badge'],
                    'order' => ['label' => 'Urutan', 'type' => 'number'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama', 'type' => 'text', 'rules' => 'required'],
                    'slug' => ['label' => 'Slug', 'type' => 'slug', 'from' => 'name'],
                    'type' => ['label' => 'Digunakan Untuk', 'type' => 'select', 'options' => ['artikel' => 'Artikel', 'kajian' => 'Kajian', 'galeri' => 'Galeri', 'inventaris' => 'Inventaris', 'keuangan' => 'Keuangan', 'program' => 'Program', 'umkm' => 'UMKM', 'ebook' => 'E-Library', 'donasi' => 'Donasi']],
                    'icon' => ['label' => 'Ikon (lucide)', 'type' => 'text'],
                    'color' => ['label' => 'Warna', 'type' => 'color'],
                    'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            // ── Ibadah ─────────────────────────────────────────────────
            'jumat' => [
                'path' => 'khatib-jumat', 'route' => 'jumat', 'model' => Models\JumatSchedule::class,
                'title' => 'Jadwal Khatib Jumat', 'singular' => 'Jadwal Jumat', 'icon' => 'mic-vocal',
                'search' => ['theme', 'khatib'], 'sort' => ['date', 'desc'],
                'columns' => [
                    'date' => ['label' => 'Tanggal', 'type' => 'date'],
                    'theme' => ['label' => 'Tema', 'type' => 'text'],
                    'khatib' => ['label' => 'Khatib', 'type' => 'text'],
                    'imam' => ['label' => 'Imam', 'type' => 'muted'],
                    'muadzin' => ['label' => 'Muadzin', 'type' => 'muted'],
                ],
                'fields' => [
                    'date' => ['label' => 'Tanggal', 'type' => 'date', 'rules' => 'required'],
                    'theme' => ['label' => 'Tema Khutbah', 'type' => 'text', 'rules' => 'required'],
                    'khatib' => ['label' => 'Khatib', 'type' => 'text', 'rules' => 'required'],
                    'imam' => ['label' => 'Imam', 'type' => 'text'],
                    'muadzin' => ['label' => 'Muadzin', 'type' => 'text'],
                    'poster' => ['label' => 'Poster', 'type' => 'image'],
                    'attachment' => ['label' => 'Naskah (PDF)', 'type' => 'file'],
                    'summary' => ['label' => 'Ringkasan Khutbah', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'events' => [
                'path' => 'agenda', 'route' => 'events', 'model' => Models\Event::class,
                'title' => 'Kalender & Agenda', 'singular' => 'Agenda', 'icon' => 'calendar-days',
                'search' => ['title', 'location'], 'sort' => ['start_at', 'desc'],
                'columns' => [
                    'title' => ['label' => 'Kegiatan', 'type' => 'text', 'sub' => 'location'],
                    'type' => ['label' => 'Jenis', 'type' => 'badge', 'map' => 'event_types'],
                    'start_at' => ['label' => 'Mulai', 'type' => 'datetime'],
                    'end_at' => ['label' => 'Selesai', 'type' => 'datetime'],
                    'is_public' => ['label' => 'Publik', 'type' => 'bool'],
                ],
                'fields' => [
                    'title' => ['label' => 'Judul Kegiatan', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'type' => ['label' => 'Jenis', 'type' => 'select', 'options' => 'event_types'],
                    'location' => ['label' => 'Lokasi', 'type' => 'text'],
                    'start_at' => ['label' => 'Mulai', 'type' => 'datetime', 'rules' => 'required'],
                    'end_at' => ['label' => 'Selesai', 'type' => 'datetime'],
                    'all_day' => ['label' => 'Sepanjang hari', 'type' => 'toggle'],
                    'is_public' => ['label' => 'Tampil di kalender publik', 'type' => 'toggle', 'default' => true],
                    'description' => ['label' => 'Keterangan', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            // ── Program & donasi ───────────────────────────────────────
            'programs' => [
                'path' => 'program', 'route' => 'programs', 'model' => Models\Program::class,
                'title' => 'Program Masjid', 'singular' => 'Program', 'icon' => 'sparkles',
                'search' => ['title'], 'sort' => ['order', 'asc'],
                'columns' => [
                    'cover' => ['label' => '', 'type' => 'image'],
                    'title' => ['label' => 'Program', 'type' => 'text', 'sub' => 'excerpt'],
                    'type' => ['label' => 'Jenis', 'type' => 'badge'],
                    'pic' => ['label' => 'Penanggung Jawab', 'type' => 'muted'],
                    'status' => ['label' => 'Status', 'type' => 'badge'],
                ],
                'fields' => [
                    'title' => ['label' => 'Nama Program', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'slug' => ['label' => 'Slug', 'type' => 'slug', 'from' => 'title'],
                    'type' => ['label' => 'Jenis', 'type' => 'select', 'options' => ['ramadhan' => 'Ramadhan', 'qurban' => 'Qurban', 'zakat' => 'Zakat', 'tpq' => 'TPQ', 'remaja' => 'Remaja Masjid', 'baksos' => 'Bakti Sosial', 'umum' => 'Umum']],
                    'icon' => ['label' => 'Ikon (lucide)', 'type' => 'text', 'default' => 'sparkles'],
                    'pic' => ['label' => 'Penanggung Jawab', 'type' => 'text'],
                    'start_date' => ['label' => 'Mulai', 'type' => 'date'],
                    'end_date' => ['label' => 'Selesai', 'type' => 'date'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Berjalan', 'selesai' => 'Selesai', 'draft' => 'Draft']],
                    'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
                    'cover' => ['label' => 'Sampul', 'type' => 'image', 'col' => 2],
                    'excerpt' => ['label' => 'Ringkasan', 'type' => 'textarea', 'col' => 2],
                    'description' => ['label' => 'Deskripsi', 'type' => 'editor', 'col' => 2],
                ],
            ],

            'campaigns' => [
                'path' => 'campaign', 'route' => 'campaigns', 'model' => Models\Campaign::class,
                'title' => 'Campaign Donasi', 'singular' => 'Campaign', 'icon' => 'hand-heart',
                'search' => ['title'], 'sort' => ['created_at', 'desc'], 'with' => ['category'],
                'columns' => [
                    'cover' => ['label' => '', 'type' => 'image'],
                    'title' => ['label' => 'Campaign', 'type' => 'text', 'sub' => 'excerpt'],
                    'target' => ['label' => 'Target', 'type' => 'money'],
                    'collected' => ['label' => 'Terkumpul', 'type' => 'money'],
                    'progress' => ['label' => 'Progres', 'type' => 'progress'],
                    'status' => ['label' => 'Status', 'type' => 'badge'],
                ],
                'fields' => [
                    'title' => ['label' => 'Judul Campaign', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'slug' => ['label' => 'Slug', 'type' => 'slug', 'from' => 'title'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'donasi']],
                    'target' => ['label' => 'Target Dana', 'type' => 'money', 'rules' => 'required|numeric'],
                    'deadline' => ['label' => 'Batas Waktu', 'type' => 'date'],
                    'start_date' => ['label' => 'Mulai', 'type' => 'date'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['active' => 'Aktif', 'finished' => 'Selesai', 'draft' => 'Draft']],
                    'is_featured' => ['label' => 'Tampilkan di beranda', 'type' => 'toggle'],
                    'cover' => ['label' => 'Sampul', 'type' => 'image', 'col' => 2],
                    'excerpt' => ['label' => 'Ringkasan', 'type' => 'textarea', 'col' => 2],
                    'description' => ['label' => 'Cerita Lengkap', 'type' => 'editor', 'col' => 2],
                ],
            ],

            'donations' => [
                'path' => 'donasi', 'route' => 'donations', 'model' => Models\Donation::class,
                'title' => 'Transaksi Donasi', 'singular' => 'Donasi', 'icon' => 'receipt',
                'search' => ['code', 'name', 'phone'], 'sort' => ['created_at', 'desc'], 'with' => ['campaign', 'channel'],
                'columns' => [
                    'code' => ['label' => 'Kode', 'type' => 'muted'],
                    'name' => ['label' => 'Donatur', 'type' => 'text', 'sub' => 'phone'],
                    'campaign.title' => ['label' => 'Campaign', 'type' => 'muted'],
                    'amount' => ['label' => 'Nominal', 'type' => 'money'],
                    'type' => ['label' => 'Jenis', 'type' => 'badge', 'map' => Models\Donation::TYPES],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['pending' => 'Menunggu', 'paid' => 'Lunas', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa']],
                    'created_at' => ['label' => 'Waktu', 'type' => 'datetime'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Donatur', 'type' => 'text', 'rules' => 'required'],
                    'phone' => ['label' => 'Telepon', 'type' => 'text'],
                    'campaign_id' => ['label' => 'Campaign', 'type' => 'select', 'options' => ['model', Models\Campaign::class, 'title']],
                    'payment_channel_id' => ['label' => 'Kanal Pembayaran', 'type' => 'select', 'options' => ['model', Models\PaymentChannel::class, 'name']],
                    'amount' => ['label' => 'Nominal', 'type' => 'money', 'rules' => 'required|numeric|min:1000'],
                    'type' => ['label' => 'Jenis', 'type' => 'select', 'options' => Models\Donation::TYPES],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Menunggu', 'paid' => 'Lunas', 'failed' => 'Gagal', 'expired' => 'Kedaluwarsa']],
                    'is_anonymous' => ['label' => 'Sembunyikan nama (Hamba Allah)', 'type' => 'toggle'],
                    'proof' => ['label' => 'Bukti Transfer', 'type' => 'image'],
                    'message' => ['label' => 'Pesan / Doa', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'channels' => [
                'path' => 'kanal-pembayaran', 'route' => 'channels', 'model' => Models\PaymentChannel::class,
                'title' => 'Kanal Pembayaran', 'singular' => 'Kanal', 'icon' => 'credit-card',
                'search' => ['name'], 'sort' => ['order', 'asc'],
                'columns' => [
                    'logo' => ['label' => '', 'type' => 'image'],
                    'name' => ['label' => 'Nama', 'type' => 'text', 'sub' => 'account_number'],
                    'type' => ['label' => 'Tipe', 'type' => 'badge'],
                    'account_name' => ['label' => 'Atas Nama', 'type' => 'muted'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'bool'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Kanal', 'type' => 'text', 'rules' => 'required'],
                    'type' => ['label' => 'Tipe', 'type' => 'select', 'options' => ['transfer' => 'Transfer Bank', 'qris' => 'QRIS', 'ewallet' => 'E-Wallet', 'tunai' => 'Tunai', 'gateway' => 'Payment Gateway']],
                    'account_number' => ['label' => 'Nomor Rekening / VA', 'type' => 'text'],
                    'account_name' => ['label' => 'Atas Nama', 'type' => 'text'],
                    'logo' => ['label' => 'Logo', 'type' => 'image'],
                    'qr_image' => ['label' => 'Gambar QRIS', 'type' => 'image'],
                    'order' => ['label' => 'Urutan', 'type' => 'number', 'default' => 0],
                    'is_active' => ['label' => 'Aktif', 'type' => 'toggle', 'default' => true],
                    'instruction' => ['label' => 'Instruksi Pembayaran', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'zakat' => [
                'path' => 'zakat', 'route' => 'zakat', 'model' => Models\ZakatPayment::class,
                'title' => 'Pembayaran Zakat', 'singular' => 'Zakat', 'icon' => 'coins',
                'search' => ['code', 'name'], 'sort' => ['created_at', 'desc'],
                'columns' => [
                    'code' => ['label' => 'Kode', 'type' => 'muted'],
                    'name' => ['label' => 'Muzakki', 'type' => 'text', 'sub' => 'phone'],
                    'type' => ['label' => 'Jenis', 'type' => 'badge', 'map' => Models\ZakatPayment::TYPES],
                    'amount' => ['label' => 'Nominal', 'type' => 'money'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['pending' => 'Menunggu', 'paid' => 'Lunas']],
                    'created_at' => ['label' => 'Waktu', 'type' => 'datetime'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Muzakki', 'type' => 'text', 'rules' => 'required'],
                    'phone' => ['label' => 'Telepon', 'type' => 'text'],
                    'type' => ['label' => 'Jenis Zakat', 'type' => 'select', 'options' => Models\ZakatPayment::TYPES],
                    'people' => ['label' => 'Jumlah Jiwa', 'type' => 'number', 'default' => 1],
                    'base_amount' => ['label' => 'Dasar Perhitungan', 'type' => 'money'],
                    'amount' => ['label' => 'Nominal Zakat', 'type' => 'money', 'rules' => 'required|numeric'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Menunggu', 'paid' => 'Lunas']],
                    'note' => ['label' => 'Catatan', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'qurban' => [
                'path' => 'qurban', 'route' => 'qurban', 'model' => Models\QurbanAnimal::class,
                'title' => 'Qurban', 'singular' => 'Hewan Qurban', 'icon' => 'beef',
                'search' => ['code', 'type'], 'sort' => ['year', 'desc'],
                'columns' => [
                    'photo' => ['label' => '', 'type' => 'image'],
                    'code' => ['label' => 'Kode', 'type' => 'text', 'sub' => 'type'],
                    'year' => ['label' => 'Tahun', 'type' => 'number'],
                    'slots_taken' => ['label' => 'Slot Terisi', 'type' => 'number'],
                    'slots' => ['label' => 'Total Slot', 'type' => 'number'],
                    'price_per_slot' => ['label' => 'Harga/Slot', 'type' => 'money'],
                    'status' => ['label' => 'Status', 'type' => 'badge'],
                ],
                'fields' => [
                    'year' => ['label' => 'Tahun', 'type' => 'number', 'rules' => 'required', 'default' => 'year'],
                    'code' => ['label' => 'Kode Hewan', 'type' => 'text'],
                    'type' => ['label' => 'Jenis', 'type' => 'select', 'options' => ['sapi' => 'Sapi', 'kambing' => 'Kambing', 'domba' => 'Domba']],
                    'slots' => ['label' => 'Jumlah Slot', 'type' => 'number', 'default' => 1],
                    'slots_taken' => ['label' => 'Slot Terisi', 'type' => 'number', 'default' => 0],
                    'price_per_slot' => ['label' => 'Harga per Slot', 'type' => 'money'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['open' => 'Dibuka', 'full' => 'Penuh', 'disembelih' => 'Disembelih', 'distribusi' => 'Distribusi', 'selesai' => 'Selesai']],
                    'photo' => ['label' => 'Foto', 'type' => 'image'],
                    'description' => ['label' => 'Keterangan', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            // ── TPQ ────────────────────────────────────────────────────
            'tpq.classes' => [
                'path' => 'tpq/kelas', 'route' => 'tpq.classes', 'model' => Models\TpqClass::class,
                'title' => 'Kelas TPQ', 'singular' => 'Kelas', 'icon' => 'graduation-cap',
                'search' => ['name', 'teacher'], 'sort' => ['name', 'asc'], 'counts' => ['students'],
                'columns' => [
                    'name' => ['label' => 'Kelas', 'type' => 'text', 'sub' => 'level'],
                    'teacher' => ['label' => 'Pengajar', 'type' => 'text'],
                    'schedule' => ['label' => 'Jadwal', 'type' => 'muted'],
                    'students_count' => ['label' => 'Santri', 'type' => 'number'],
                    'fee' => ['label' => 'SPP', 'type' => 'money'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Kelas', 'type' => 'text', 'rules' => 'required'],
                    'level' => ['label' => 'Jenjang', 'type' => 'text'],
                    'teacher' => ['label' => 'Pengajar', 'type' => 'text', 'rules' => 'required'],
                    'schedule' => ['label' => 'Jadwal', 'type' => 'text'],
                    'room' => ['label' => 'Ruang', 'type' => 'text'],
                    'fee' => ['label' => 'SPP per Bulan', 'type' => 'money'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'toggle', 'default' => true],
                ],
            ],

            'tpq.students' => [
                'path' => 'tpq/santri', 'route' => 'tpq.students', 'model' => Models\TpqStudent::class,
                'title' => 'Santri TPQ', 'singular' => 'Santri', 'icon' => 'users-round',
                'search' => ['nis', 'name', 'parent_name'], 'sort' => ['name', 'asc'], 'with' => ['tpqClass'],
                'columns' => [
                    'photo' => ['label' => '', 'type' => 'avatar'],
                    'nis' => ['label' => 'NIS', 'type' => 'muted'],
                    'name' => ['label' => 'Nama', 'type' => 'text', 'sub' => 'parent_name'],
                    'tpqClass.name' => ['label' => 'Kelas', 'type' => 'badge'],
                    'phone' => ['label' => 'Kontak', 'type' => 'muted'],
                    'status' => ['label' => 'Status', 'type' => 'badge'],
                ],
                'fields' => [
                    'nis' => ['label' => 'NIS', 'type' => 'text', 'rules' => 'required'],
                    'name' => ['label' => 'Nama Santri', 'type' => 'text', 'rules' => 'required'],
                    'tpq_class_id' => ['label' => 'Kelas', 'type' => 'select', 'options' => ['model', Models\TpqClass::class, 'name']],
                    'gender' => ['label' => 'Jenis Kelamin', 'type' => 'select', 'options' => ['L' => 'Laki-laki', 'P' => 'Perempuan']],
                    'birth_date' => ['label' => 'Tanggal Lahir', 'type' => 'date'],
                    'parent_name' => ['label' => 'Nama Orang Tua', 'type' => 'text'],
                    'phone' => ['label' => 'Telepon Orang Tua', 'type' => 'text'],
                    'joined_at' => ['label' => 'Tanggal Masuk', 'type' => 'date'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['aktif' => 'Aktif', 'lulus' => 'Lulus', 'keluar' => 'Keluar']],
                    'photo' => ['label' => 'Foto', 'type' => 'image'],
                    'address' => ['label' => 'Alamat', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'tpq.payments' => [
                'path' => 'tpq/spp', 'route' => 'tpq.payments', 'model' => Models\TpqPayment::class,
                'title' => 'SPP TPQ', 'singular' => 'Pembayaran SPP', 'icon' => 'wallet-cards',
                'search' => ['period'], 'sort' => ['period', 'desc'], 'with' => ['student'],
                'columns' => [
                    'student.name' => ['label' => 'Santri', 'type' => 'text'],
                    'period' => ['label' => 'Periode', 'type' => 'text'],
                    'amount' => ['label' => 'Nominal', 'type' => 'money'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['belum' => 'Belum Bayar', 'lunas' => 'Lunas']],
                    'paid_at' => ['label' => 'Dibayar', 'type' => 'date'],
                ],
                'fields' => [
                    'tpq_student_id' => ['label' => 'Santri', 'type' => 'select', 'options' => ['model', Models\TpqStudent::class, 'name'], 'rules' => 'required'],
                    'period' => ['label' => 'Periode (YYYY-MM)', 'type' => 'text', 'rules' => 'required'],
                    'amount' => ['label' => 'Nominal', 'type' => 'money', 'rules' => 'required|numeric'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['belum' => 'Belum Bayar', 'lunas' => 'Lunas']],
                    'paid_at' => ['label' => 'Tanggal Bayar', 'type' => 'date'],
                ],
            ],

            // ── Keuangan ───────────────────────────────────────────────
            'transactions' => [
                'path' => 'keuangan/transaksi', 'route' => 'transactions', 'model' => Models\Transaction::class,
                'title' => 'Transaksi Keuangan', 'singular' => 'Transaksi', 'icon' => 'arrow-right-left',
                'search' => ['code', 'description'], 'sort' => ['date', 'desc'], 'with' => ['category', 'account'],
                'columns' => [
                    'date' => ['label' => 'Tanggal', 'type' => 'date'],
                    'description' => ['label' => 'Keterangan', 'type' => 'text', 'sub' => 'code'],
                    'category.name' => ['label' => 'Kategori', 'type' => 'badge'],
                    'account.name' => ['label' => 'Rekening', 'type' => 'muted'],
                    'type' => ['label' => 'Jenis', 'type' => 'badge', 'map' => ['in' => 'Pemasukan', 'out' => 'Pengeluaran']],
                    'amount' => ['label' => 'Nominal', 'type' => 'money'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['draft' => 'Draft', 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak']],
                ],
                'fields' => [
                    'date' => ['label' => 'Tanggal', 'type' => 'date', 'rules' => 'required', 'default' => 'today'],
                    'type' => ['label' => 'Jenis', 'type' => 'select', 'options' => ['in' => 'Pemasukan', 'out' => 'Pengeluaran'], 'rules' => 'required'],
                    'description' => ['label' => 'Keterangan', 'type' => 'text', 'rules' => 'required', 'col' => 2],
                    'amount' => ['label' => 'Nominal', 'type' => 'money', 'rules' => 'required|numeric|min:1'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'keuangan']],
                    'finance_account_id' => ['label' => 'Rekening', 'type' => 'select', 'options' => ['model', Models\FinanceAccount::class, 'name']],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draft', 'pending' => 'Menunggu Persetujuan', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'], 'default' => 'approved'],
                    'proof' => ['label' => 'Bukti', 'type' => 'image', 'col' => 2],
                ],
            ],

            'accounts' => [
                'path' => 'keuangan/rekening', 'route' => 'accounts', 'model' => Models\FinanceAccount::class,
                'title' => 'Rekening & Kas', 'singular' => 'Rekening', 'icon' => 'landmark',
                'search' => ['name'], 'sort' => ['name', 'asc'],
                'columns' => [
                    'name' => ['label' => 'Nama', 'type' => 'text', 'sub' => 'number'],
                    'type' => ['label' => 'Tipe', 'type' => 'badge', 'map' => ['kas' => 'Kas Tunai', 'bank' => 'Bank']],
                    'opening_balance' => ['label' => 'Saldo Awal', 'type' => 'money'],
                    'balance' => ['label' => 'Saldo Sekarang', 'type' => 'money'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'bool'],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Rekening', 'type' => 'text', 'rules' => 'required'],
                    'type' => ['label' => 'Tipe', 'type' => 'select', 'options' => ['kas' => 'Kas Tunai', 'bank' => 'Bank']],
                    'number' => ['label' => 'Nomor Rekening', 'type' => 'text'],
                    'opening_balance' => ['label' => 'Saldo Awal', 'type' => 'money'],
                    'is_active' => ['label' => 'Aktif', 'type' => 'toggle', 'default' => true],
                ],
            ],

            // ── Aset & layanan ─────────────────────────────────────────
            'inventories' => [
                'path' => 'inventaris/aset', 'route' => 'inventories', 'model' => Models\Inventory::class,
                'title' => 'Aset & Inventaris', 'singular' => 'Aset', 'icon' => 'package',
                'search' => ['code', 'name'], 'sort' => ['name', 'asc'], 'with' => ['category'],
                'columns' => [
                    'photo' => ['label' => '', 'type' => 'image'],
                    'name' => ['label' => 'Nama Aset', 'type' => 'text', 'sub' => 'code'],
                    'category.name' => ['label' => 'Kategori', 'type' => 'badge'],
                    'quantity' => ['label' => 'Jumlah', 'type' => 'number'],
                    'condition' => ['label' => 'Kondisi', 'type' => 'badge', 'map' => Models\Inventory::CONDITIONS],
                    'location' => ['label' => 'Lokasi', 'type' => 'muted'],
                    'price' => ['label' => 'Nilai', 'type' => 'money'],
                ],
                'fields' => [
                    'code' => ['label' => 'Kode Aset', 'type' => 'text', 'rules' => 'required'],
                    'name' => ['label' => 'Nama Aset', 'type' => 'text', 'rules' => 'required'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'inventaris']],
                    'quantity' => ['label' => 'Jumlah', 'type' => 'number', 'default' => 1],
                    'unit' => ['label' => 'Satuan', 'type' => 'text', 'default' => 'unit'],
                    'condition' => ['label' => 'Kondisi', 'type' => 'select', 'options' => Models\Inventory::CONDITIONS],
                    'purchase_date' => ['label' => 'Tanggal Perolehan', 'type' => 'date'],
                    'price' => ['label' => 'Nilai Perolehan', 'type' => 'money'],
                    'location' => ['label' => 'Lokasi Penyimpanan', 'type' => 'text'],
                    'is_lendable' => ['label' => 'Boleh dipinjam jamaah', 'type' => 'toggle'],
                    'photo' => ['label' => 'Foto', 'type' => 'image', 'col' => 2],
                    'note' => ['label' => 'Catatan', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'maintenances' => [
                'path' => 'inventaris/perawatan', 'route' => 'maintenances', 'model' => Models\InventoryMaintenance::class,
                'title' => 'Perawatan Aset', 'singular' => 'Perawatan', 'icon' => 'wrench',
                'search' => ['description', 'vendor'], 'sort' => ['date', 'desc'], 'with' => ['inventory'],
                'columns' => [
                    'date' => ['label' => 'Tanggal', 'type' => 'date'],
                    'inventory.name' => ['label' => 'Aset', 'type' => 'text'],
                    'type' => ['label' => 'Jenis', 'type' => 'badge'],
                    'cost' => ['label' => 'Biaya', 'type' => 'money'],
                    'vendor' => ['label' => 'Vendor', 'type' => 'muted'],
                    'next_due' => ['label' => 'Jadwal Berikutnya', 'type' => 'date'],
                ],
                'fields' => [
                    'inventory_id' => ['label' => 'Aset', 'type' => 'select', 'options' => ['model', Models\Inventory::class, 'name'], 'rules' => 'required'],
                    'date' => ['label' => 'Tanggal', 'type' => 'date', 'rules' => 'required', 'default' => 'today'],
                    'type' => ['label' => 'Jenis', 'type' => 'select', 'options' => ['perawatan' => 'Perawatan Rutin', 'perbaikan' => 'Perbaikan', 'penggantian' => 'Penggantian']],
                    'cost' => ['label' => 'Biaya', 'type' => 'money'],
                    'vendor' => ['label' => 'Vendor / Teknisi', 'type' => 'text'],
                    'next_due' => ['label' => 'Jadwal Berikutnya', 'type' => 'date'],
                    'description' => ['label' => 'Keterangan', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'loans' => [
                'path' => 'inventaris/peminjaman', 'route' => 'loans', 'model' => Models\InventoryLoan::class,
                'title' => 'Peminjaman Aset', 'singular' => 'Peminjaman', 'icon' => 'hand',
                'search' => ['code', 'borrower'], 'sort' => ['created_at', 'desc'], 'with' => ['inventory'],
                'columns' => [
                    'code' => ['label' => 'Kode', 'type' => 'muted'],
                    'borrower' => ['label' => 'Peminjam', 'type' => 'text', 'sub' => 'phone'],
                    'inventory.name' => ['label' => 'Aset', 'type' => 'text'],
                    'quantity' => ['label' => 'Jml', 'type' => 'number'],
                    'borrow_date' => ['label' => 'Pinjam', 'type' => 'date'],
                    'due_date' => ['label' => 'Kembali', 'type' => 'date'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'returned' => 'Dikembalikan']],
                ],
                'fields' => [
                    'inventory_id' => ['label' => 'Aset', 'type' => 'select', 'options' => ['model', Models\Inventory::class, 'name'], 'rules' => 'required'],
                    'borrower' => ['label' => 'Nama Peminjam', 'type' => 'text', 'rules' => 'required'],
                    'phone' => ['label' => 'Telepon', 'type' => 'text'],
                    'quantity' => ['label' => 'Jumlah', 'type' => 'number', 'default' => 1],
                    'borrow_date' => ['label' => 'Tanggal Pinjam', 'type' => 'date', 'rules' => 'required'],
                    'due_date' => ['label' => 'Rencana Kembali', 'type' => 'date', 'rules' => 'required'],
                    'returned_at' => ['label' => 'Dikembalikan', 'type' => 'date'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'returned' => 'Dikembalikan']],
                    'purpose' => ['label' => 'Keperluan', 'type' => 'textarea', 'col' => 2],
                    'admin_note' => ['label' => 'Catatan Admin', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'bookings' => [
                'path' => 'booking-ruangan', 'route' => 'bookings', 'model' => Models\RoomBooking::class,
                'title' => 'Booking Ruangan', 'singular' => 'Booking', 'icon' => 'door-open',
                'search' => ['code', 'name', 'purpose'], 'sort' => ['date', 'desc'], 'with' => ['room'],
                'columns' => [
                    'code' => ['label' => 'Kode', 'type' => 'muted'],
                    'name' => ['label' => 'Pemohon', 'type' => 'text', 'sub' => 'phone'],
                    'room.name' => ['label' => 'Ruangan', 'type' => 'badge'],
                    'purpose' => ['label' => 'Keperluan', 'type' => 'text'],
                    'date' => ['label' => 'Tanggal', 'type' => 'date'],
                    'start_time' => ['label' => 'Jam', 'type' => 'text'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'done' => 'Selesai']],
                ],
                'fields' => [
                    'room_id' => ['label' => 'Ruangan', 'type' => 'select', 'options' => ['model', Models\Room::class, 'name'], 'rules' => 'required'],
                    'name' => ['label' => 'Nama Pemohon', 'type' => 'text', 'rules' => 'required'],
                    'phone' => ['label' => 'Telepon', 'type' => 'text'],
                    'purpose' => ['label' => 'Keperluan', 'type' => 'text', 'rules' => 'required'],
                    'date' => ['label' => 'Tanggal', 'type' => 'date', 'rules' => 'required'],
                    'start_time' => ['label' => 'Jam Mulai', 'type' => 'time', 'rules' => 'required'],
                    'end_time' => ['label' => 'Jam Selesai', 'type' => 'time', 'rules' => 'required'],
                    'participants' => ['label' => 'Perkiraan Peserta', 'type' => 'number'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'done' => 'Selesai']],
                    'note' => ['label' => 'Catatan Pemohon', 'type' => 'textarea', 'col' => 2],
                    'admin_note' => ['label' => 'Catatan Admin', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'umkm' => [
                'path' => 'umkm', 'route' => 'umkm', 'model' => Models\UmkmBusiness::class,
                'title' => 'UMKM Jamaah', 'singular' => 'UMKM', 'icon' => 'store',
                'search' => ['name', 'owner'], 'sort' => ['created_at', 'desc'], 'with' => ['category'],
                'columns' => [
                    'logo' => ['label' => '', 'type' => 'image'],
                    'name' => ['label' => 'Usaha', 'type' => 'text', 'sub' => 'owner'],
                    'category.name' => ['label' => 'Kategori', 'type' => 'badge'],
                    'whatsapp' => ['label' => 'WhatsApp', 'type' => 'muted'],
                    'status' => ['label' => 'Status', 'type' => 'badge', 'map' => ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak']],
                ],
                'fields' => [
                    'name' => ['label' => 'Nama Usaha', 'type' => 'text', 'rules' => 'required'],
                    'slug' => ['label' => 'Slug', 'type' => 'slug', 'from' => 'name'],
                    'owner' => ['label' => 'Pemilik', 'type' => 'text', 'rules' => 'required'],
                    'category_id' => ['label' => 'Kategori', 'type' => 'select', 'options' => ['categories', 'umkm']],
                    'phone' => ['label' => 'Telepon', 'type' => 'text'],
                    'whatsapp' => ['label' => 'WhatsApp', 'type' => 'text'],
                    'instagram' => ['label' => 'Instagram', 'type' => 'text'],
                    'status' => ['label' => 'Status', 'type' => 'select', 'options' => ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak']],
                    'logo' => ['label' => 'Logo', 'type' => 'image'],
                    'cover' => ['label' => 'Sampul', 'type' => 'image'],
                    'lat' => ['label' => 'Latitude', 'type' => 'text'],
                    'lng' => ['label' => 'Longitude', 'type' => 'text'],
                    'is_featured' => ['label' => 'Unggulan', 'type' => 'toggle'],
                    'address' => ['label' => 'Alamat', 'type' => 'textarea', 'col' => 2],
                    'description' => ['label' => 'Deskripsi', 'type' => 'textarea', 'col' => 2],
                ],
            ],

            'messages' => [
                'path' => 'pesan', 'route' => 'messages', 'model' => Models\ContactMessage::class,
                'title' => 'Pesan Masuk', 'singular' => 'Pesan', 'icon' => 'mail',
                'search' => ['name', 'subject', 'message'], 'sort' => ['created_at', 'desc'], 'creatable' => false,
                'columns' => [
                    'name' => ['label' => 'Pengirim', 'type' => 'text', 'sub' => 'email'],
                    'subject' => ['label' => 'Subjek', 'type' => 'text', 'sub' => 'message'],
                    'phone' => ['label' => 'Telepon', 'type' => 'muted'],
                    'is_read' => ['label' => 'Dibaca', 'type' => 'bool'],
                    'created_at' => ['label' => 'Waktu', 'type' => 'datetime'],
                ],
                'fields' => [
                    'name' => ['label' => 'Pengirim', 'type' => 'text'],
                    'email' => ['label' => 'Email', 'type' => 'text'],
                    'phone' => ['label' => 'Telepon', 'type' => 'text'],
                    'subject' => ['label' => 'Subjek', 'type' => 'text'],
                    'is_read' => ['label' => 'Sudah dibaca', 'type' => 'toggle'],
                    'message' => ['label' => 'Pesan', 'type' => 'textarea', 'col' => 2],
                ],
            ],
        ];
    }

    public static function find(string $key): ?array
    {
        return static::all()[$key] ?? null;
    }
}
