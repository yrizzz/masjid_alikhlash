# 🌙 Masjid Jami' Al-Ikhlash

> **Sistem Informasi, Ruang Kerja Pengurus & Portal Jamaah Masjid Jami' Al-Ikhlash (Kerten, Laweyan, Surakarta)**

Aplikasi web modern berbasis **Laravel 13**, **Livewire 4**, **Tailwind CSS v4**, dan **Alpine.js**.

---

## 📖 Tentang Aplikasi

Aplikasi **Masjid Jami' Al-Ikhlash** dirancang untuk memudahkan pengelolaan kegiatan operasional masjid sekaligus memberikan keterbukaan informasi dan kemudahan layanan bagi jamaah dalam satu platform terpadu.

### 🌟 Fitur Utama

- 🕌 **Portal Publik & Informasi Masjid**: Informasi jadwal sholat otomatis dengan iqomah, jadwal kajian rutin/tematik, pengumuman, dan artikel keislaman.
- 💰 **Laporan Keuangan Transparan**: Laporan kas masuk/keluar, infak, sedekah, dan zakat tercatat rapi secara realtime.
- 📇 **Kartu Anggota Digital ber-QR**: Kartu keanggotaan digital jamaah yang praktis dan terintegrasi QR code.
- 📖 **Pengelolaan TPQ & Kajian**: Manajemen santri TPQ serta penjadwalan kajian keilmuan.
- 🎨 **Panel Pengurus & Customizer UI**: Ruang kerja pengurus responsif dengan dukungan tema (Light & Dark mode) serta opsi aksen warna (Default: Coklat Warm Earthy).

---

## 🛠️ Teknologi yang Digunakan

- **Framework Backend**: [Laravel 13](https://laravel.com)
- **Reaktivitas Frontend**: [Livewire 4](https://livewire.laravel.com) & [Alpine.js](https://alpinejs.dev)
- **Styling**: [Tailwind CSS v4](https://tailwindcss.com)
- **Ikon**: [Lucide Icons](https://lucide.dev) & Custom SVG Components
- **Bundler Asset**: [Vite](https://vitejs.dev)

---

## 🚀 Panduan Pengoperasian

### 1. Clone Repository & Install Dependensi

```bash
git clone https://github.com/tecmaadv/masjid_alikhlash.git
cd masjid_alikhlash

# Install dependensi PHP & Node.js
composer install
npm install
```

### 2. Konfigurasi Environment & Database

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

### 3. Build Asset & Jalankan Server

```bash
# Compile asset Vite
npm run build

# Jalankan server lokal
php artisan dev
```

---

## 🔑 Kredensial Demo Pengurus

| Parameter | Nilai |
| :--- | :--- |
| **URL Aplikasi** | `http://localhost:8000` |
| **Email Pengurus** | `admin@alikhlash.test` |
| **Kata Sandi** | `password` |

---

## 📝 Lisensi

Aplikasi ini dikembangkan untuk kebutuhan operasional **Masjid Jami' Al-Ikhlash Kerten**.
