<?php

namespace App\Livewire\Pub;

use App\Models\Category;
use App\Models\FinanceAccount;
use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class Transparansi extends Component
{
    use WithPagination;

    public string $range = 'bulan';
    public string $from = '';
    public string $to = '';
    public string $type = '';
    public ?int $categoryId = null;

    public function mount(): void
    {
        $this->setRange('bulan');
    }

    public function setRange(string $range): void
    {
        $this->range = $range;

        [$from, $to] = match ($range) {
            'hari'  => [today(), today()],
            'bulan' => [today()->startOfMonth(), today()->endOfMonth()],
            'tahun' => [today()->startOfYear(), today()->endOfYear()],
            default => [today()->subYears(5), today()],
        };

        $this->from = $from->format('Y-m-d');
        $this->to   = $to->format('Y-m-d');
        $this->resetPage();
    }

    protected function base()
    {
        $q = Transaction::approved()
            ->whereDate('date', '>=', $this->from)
            ->whereDate('date', '<=', $this->to);

        if ($this->type !== '') {
            $q->where('type', $this->type);
        }
        if ($this->categoryId) {
            $q->where('category_id', $this->categoryId);
        }

        return $q;
    }

    public function render()
    {
        $income  = (float) Transaction::approved()->whereDate('date', '>=', $this->from)->whereDate('date', '<=', $this->to)->income()->sum('amount');
        $expense = (float) Transaction::approved()->whereDate('date', '>=', $this->from)->whereDate('date', '<=', $this->to)->expense()->sum('amount');

        // Grafik 12 bulan terakhir.
        $labels = $inSeries = $outSeries = [];
        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $labels[]    = $m->translatedFormat('M y');
            $inSeries[]  = (float) Transaction::approved()->income()->whereYear('date', $m->year)->whereMonth('date', $m->month)->sum('amount');
            $outSeries[] = (float) Transaction::approved()->expense()->whereYear('date', $m->year)->whereMonth('date', $m->month)->sum('amount');
        }

        $byCategory = Transaction::approved()
            ->whereDate('date', '>=', $this->from)->whereDate('date', '<=', $this->to)
            ->expense()->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')->orderByDesc('total')->get()
            ->map(fn ($r) => ['name' => Category::find($r->category_id)?->name ?? 'Lain-lain', 'total' => (float) $r->total]);

        return view('livewire.pub.transparansi', [
            'income'     => $income,
            'expense'    => $expense,
            'balance'    => FinanceAccount::where('is_active', true)->get()->sum(fn ($a) => $a->balance),
            'rows'       => $this->base()->with(['category', 'account'])->orderByDesc('date')->paginate(15),
            'categories' => Category::type('keuangan')->get(),
            'labels'     => $labels,
            'inSeries'   => $inSeries,
            'outSeries'  => $outSeries,
            'byCategory' => $byCategory,
            'accounts'   => FinanceAccount::where('is_active', true)->get(),
        ])->layout('components.layouts.public', ['title' => 'Transparansi Keuangan']);
    }
}
