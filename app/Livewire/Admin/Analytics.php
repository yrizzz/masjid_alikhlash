<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Models\Kajian;
use App\Models\PageView;
use Livewire\Component;

class Analytics extends Component
{
    public int $days = 30;

    public function render()
    {
        $since = today()->subDays($this->days);

        $labels = $series = [];
        for ($i = $this->days - 1; $i >= 0; $i--) {
            $d = today()->subDays($i);
            $labels[] = $d->format('d/m');
            $series[] = PageView::whereDate('date', $d)->count();
        }

        return view('livewire.admin.analytics', [
            'labels'   => $labels,
            'series'   => $series,
            'total'    => PageView::where('date', '>=', $since)->count(),
            'unique'   => PageView::where('date', '>=', $since)->distinct('ip')->count('ip'),
            'popular'  => PageView::where('date', '>=', $since)
                ->selectRaw('path, COUNT(*) as hits')->groupBy('path')->orderByDesc('hits')->take(12)->get(),
            'articles' => Article::orderByDesc('views')->take(8)->get(),
            'kajians'  => Kajian::orderByDesc('views')->take(8)->get(),
        ])->layout('components.layouts.app', [
            'title'       => 'Analitik',
            'breadcrumbs' => [['label' => 'Sistem'], ['label' => 'Analitik']],
        ]);
    }
}
