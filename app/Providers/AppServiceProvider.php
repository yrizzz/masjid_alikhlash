<?php

namespace App\Providers;

use App\Services\HijriService;
use App\Services\PrayerTimeService;
use App\Services\ZakatService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PrayerTimeService::class);
        $this->app->singleton(HijriService::class);
        $this->app->singleton(ZakatService::class);
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::useTailwind();

        Relation::enforceMorphMap([
            'kajian'  => \App\Models\Kajian::class,
            'artikel' => \App\Models\Article::class,
            'ebook'   => \App\Models\Ebook::class,
            'event'   => \App\Models\Event::class,
        ]);

        // Waktu sholat & tanggal hijriah tersedia di semua layout publik.
        View::composer(['components.layouts.public', 'partials.public.*'], function ($view) {
            $view->with([
                'prayerStatus' => app(PrayerTimeService::class)->status(),
                'hijri'        => app(HijriService::class)->convert(),
            ]);
        });

        Blade::directive('rupiah', fn ($e) => "<?php echo rupiah($e); ?>");
        Blade::directive('rupiahShort', fn ($e) => "<?php echo rupiah_short($e); ?>");
        Blade::directive('tanggal', fn ($e) => "<?php echo tanggal_id($e); ?>");
    }
}
