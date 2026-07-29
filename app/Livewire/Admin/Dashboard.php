<?php

namespace App\Livewire\Admin;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\FinanceAccount;
use App\Models\Kajian;
use App\Models\PageView;
use App\Models\Program;
use App\Models\RoomBooking;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Volunteer;
use App\Services\HijriService;
use App\Services\PrayerTimeService;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $month = now()->startOfMonth();

        $income  = (float) Transaction::approved()->income()->where('date', '>=', $month)->sum('amount');
        $expense = (float) Transaction::approved()->expense()->where('date', '>=', $month)->sum('amount');
        $balance = FinanceAccount::where('is_active', true)->get()->sum(fn ($a) => $a->balance);

        $stats = [
            ['label' => 'Total Jamaah',      'value' => number_format(User::count(), 0, ',', '.'), 'icon' => 'users', 'tone' => 'primary'],
            ['label' => 'Donasi Bulan Ini',  'value' => rupiah_short(Donation::paid()->where('paid_at', '>=', $month)->sum('amount')), 'icon' => 'hand-heart', 'tone' => 'success'],
            ['label' => 'Saldo Kas',         'value' => rupiah_short($balance), 'icon' => 'wallet', 'tone' => 'info'],
            ['label' => 'Pengeluaran Bulan Ini', 'value' => rupiah_short($expense), 'icon' => 'trending-down', 'tone' => 'destructive'],
            ['label' => 'Kajian Hari Ini',   'value' => Kajian::published()->whereDate('start_at', today())->count(), 'icon' => 'book-open', 'tone' => 'primary'],
            ['label' => 'Program Aktif',     'value' => Program::active()->count(), 'icon' => 'sparkles', 'tone' => 'warning'],
            ['label' => 'Volunteer Aktif',   'value' => Volunteer::where('status', 'active')->count(), 'icon' => 'hand-helping', 'tone' => 'success'],
            ['label' => 'Pengunjung 30 Hari','value' => number_format(PageView::where('date', '>=', today()->subDays(30))->count(), 0, ',', '.'), 'icon' => 'chart-line', 'tone' => 'info'],
        ];

        // Grafik: 12 bulan terakhir pemasukan vs pengeluaran.
        $labels = $inSeries = $outSeries = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $labels[] = $m->translatedFormat('M');
            $inSeries[]  = (float) Transaction::approved()->income()->whereYear('date', $m->year)->whereMonth('date', $m->month)->sum('amount');
            $outSeries[] = (float) Transaction::approved()->expense()->whereYear('date', $m->year)->whereMonth('date', $m->month)->sum('amount');
        }

        // Traffic 14 hari terakhir.
        $trafficLabels = $trafficData = [];
        for ($i = 13; $i >= 0; $i--) {
            $d = today()->subDays($i);
            $trafficLabels[] = $d->format('d/m');
            $trafficData[]   = PageView::whereDate('date', $d)->count();
        }

        return view('livewire.admin.dashboard', [
            'stats'         => $stats,
            'income'        => $income,
            'expense'       => $expense,
            'balance'       => $balance,
            'chartLabels'   => $labels,
            'chartIn'       => $inSeries,
            'chartOut'      => $outSeries,
            'trafficLabels' => $trafficLabels,
            'trafficData'   => $trafficData,
            'campaigns'     => Campaign::active()->orderByDesc('collected')->take(4)->get(),
            'donations'     => Donation::with('campaign')->latest()->take(6)->get(),
            'kajians'       => Kajian::published()->upcoming()->take(5)->get(),
            'bookings'      => RoomBooking::with('room')->where('status', 'pending')->latest()->take(5)->get(),
            'prayer'        => app(PrayerTimeService::class)->status(),
            'hijri'         => app(HijriService::class)->convert(),
        ])->layout('components.layouts.app', [
            'title'       => 'Dashboard',
            'breadcrumbs' => [['label' => 'Admin'], ['label' => 'Dashboard']],
        ]);
    }
}
