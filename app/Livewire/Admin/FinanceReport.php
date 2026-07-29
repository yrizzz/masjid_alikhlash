<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\FinanceAccount;
use App\Models\Transaction;
use Livewire\Component;

class FinanceReport extends Component
{
    public string $from = '';
    public string $to = '';
    public string $preset = 'bulan';

    public function mount(): void
    {
        $this->applyPreset('bulan');
    }

    public function applyPreset(string $preset): void
    {
        $this->preset = $preset;

        [$from, $to] = match ($preset) {
            'hari'  => [today(), today()],
            'pekan' => [today()->startOfWeek(), today()->endOfWeek()],
            'tahun' => [today()->startOfYear(), today()->endOfYear()],
            default => [today()->startOfMonth(), today()->endOfMonth()],
        };

        $this->from = $from->format('Y-m-d');
        $this->to   = $to->format('Y-m-d');
    }

    protected function base()
    {
        return Transaction::approved()
            ->whereDate('date', '>=', $this->from ?: '1970-01-01')
            ->whereDate('date', '<=', $this->to ?: '2999-12-31');
    }

    public function render()
    {
        $income  = (float) (clone $this->base())->income()->sum('amount');
        $expense = (float) (clone $this->base())->expense()->sum('amount');

        // Rincian per kategori.
        $byCategory = (clone $this->base())->expense()
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')->orderByDesc('total')->get()
            ->map(fn ($r) => [
                'name'  => Category::find($r->category_id)?->name ?? 'Lain-lain',
                'total' => (float) $r->total,
            ]);

        // Arus kas harian dalam rentang. Alias `d` dipakai agar hasilnya tetap
        // berupa string tanggal — kolom `date` di-cast ke Carbon oleh model.
        $daily = (clone $this->base())
            ->selectRaw('DATE(date) as d, type, SUM(amount) as total')
            ->groupBy('d', 'type')->orderBy('d')->get();

        $dates  = $daily->pluck('d')->unique()->values();
        $labels = $dates->map(fn ($d) => \Carbon\Carbon::parse($d)->format('d/m'));
        $inMap  = $daily->where('type', 'in')->pluck('total', 'd');
        $outMap = $daily->where('type', 'out')->pluck('total', 'd');

        return view('livewire.admin.finance-report', [
            'income'     => $income,
            'expense'    => $expense,
            'balance'    => $income - $expense,
            'accounts'   => FinanceAccount::where('is_active', true)->get(),
            'byCategory' => $byCategory,
            'rows'       => (clone $this->base())->with(['category', 'account'])->orderByDesc('date')->paginate(20),
            'labels'     => $labels,
            'inSeries'   => $dates->map(fn ($d) => (float) ($inMap[$d] ?? 0)),
            'outSeries'  => $dates->map(fn ($d) => (float) ($outMap[$d] ?? 0)),
        ])->layout('components.layouts.app', [
            'title'       => 'Laporan Keuangan',
            'breadcrumbs' => [['label' => 'Keuangan'], ['label' => 'Laporan']],
        ]);
    }
}
