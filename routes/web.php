<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\PlaceholderController;
use App\Livewire\Admin;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Pub;
use App\Support\Resources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Situs publik
|--------------------------------------------------------------------------
*/
Route::get('/', Pub\Home::class)->name('home');
Route::get('/profil', Pub\Profil::class)->name('profil');

Route::get('/jadwal-sholat', Pub\Jadwal::class)->name('jadwal');
Route::get('/jadwal-imam', Pub\Imam::class)->name('imam');
Route::get('/khatib-jumat', Pub\Jumat::class)->name('jumat');
Route::get('/arah-kiblat', Pub\Kiblat::class)->name('kiblat');
Route::get('/al-quran/{surah?}', Pub\Quran::class)->name('quran');

Route::get('/kajian', Pub\KajianIndex::class)->name('kajian');
Route::get('/kajian/{kajian:slug}', Pub\KajianShow::class)->name('kajian.show');
Route::get('/kalender', Pub\Kalender::class)->name('kalender');
Route::get('/live', Pub\Live::class)->name('live');
Route::get('/program', Pub\ProgramIndex::class)->name('program');
Route::get('/program/{program:slug}', Pub\ProgramShow::class)->name('program.show');
Route::get('/galeri', Pub\GaleriIndex::class)->name('galeri');
Route::get('/galeri/{gallery:slug}', Pub\GaleriShow::class)->name('galeri.show');

Route::get('/donasi', Pub\DonasiIndex::class)->name('donasi');
Route::get('/donasi/{campaign:slug}', Pub\DonasiShow::class)->name('donasi.show');
Route::get('/transparansi', Pub\Transparansi::class)->name('transparansi');
Route::get('/zakat', Pub\Zakat::class)->name('zakat');
Route::get('/qurban', Pub\Qurban::class)->name('qurban');

Route::get('/booking-ruangan', Pub\Booking::class)->name('booking');
Route::get('/peminjaman', Pub\Pinjam::class)->name('pinjam');
Route::get('/volunteer', Pub\VolunteerPage::class)->name('volunteer');
Route::get('/umkm', Pub\UmkmIndex::class)->name('umkm');
Route::get('/umkm/{business:slug}', Pub\UmkmShow::class)->name('umkm.show');
Route::get('/pustaka', Pub\Pustaka::class)->name('pustaka');

Route::get('/artikel', Pub\ArtikelIndex::class)->name('artikel');
Route::get('/artikel/{article:slug}', Pub\ArtikelShow::class)->name('artikel.show');
Route::get('/kontak', Pub\Kontak::class)->name('kontak');
Route::get('/faq', Pub\FaqPage::class)->name('faq');
Route::get('/cari', Pub\Search::class)->name('search');
Route::get('/tanya', Pub\Assistant::class)->name('assistant');
Route::get('/halaman/{page:slug}', Pub\StaticPage::class)->name('halaman');

Route::get('/img/{seed}.svg', PlaceholderController::class)->name('placeholder');

/*
|--------------------------------------------------------------------------
| Area jamaah (perlu login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/akun', Pub\Akun::class)->name('akun');
    Route::get('/akun/kartu', Pub\MemberCard::class)->name('akun.kartu');
});

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\Dashboard::class)->name('dashboard');

    // Modul CRUD generik — definisinya di App\Support\Resources.
    foreach (Resources::all() as $key => $def) {
        Route::get('/'.$def['path'], Admin\Resource::class)
            ->name($def['route'])
            ->defaults('resource', $key);
    }

    Route::get('/profil-masjid', Admin\Profile::class)->name('profile');
    Route::get('/waktu-sholat', Admin\Prayer::class)->name('prayer');
    Route::get('/jadwal-imam', Admin\ImamBoard::class)->name('imam');
    Route::get('/keuangan/laporan', Admin\FinanceReport::class)->name('finance.report');
    Route::get('/tpq/absensi', Admin\TpqAttendanceBoard::class)->name('tpq.attendance');
    Route::get('/tpq/nilai', Admin\TpqGradeBoard::class)->name('tpq.grades');
    Route::get('/qr-checkin', Admin\Checkin::class)->name('checkin');
    Route::get('/media', Admin\MediaManager::class)->name('media');
    Route::get('/analitik', Admin\Analytics::class)->name('analytics');
    Route::get('/pengaturan', Admin\Settings::class)->name('settings');

    Route::get('/export/{type}', ExportController::class)->name('export');
});
