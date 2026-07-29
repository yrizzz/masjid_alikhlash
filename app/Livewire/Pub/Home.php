<?php

namespace App\Livewire\Pub;

use App\Models\Article;
use App\Models\Banner;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\JumatSchedule;
use App\Models\Kajian;
use App\Models\Livestream;
use App\Models\Program;
use App\Models\RunningText;
use App\Models\Transaction;
use App\Models\UmkmBusiness;
use App\Services\HijriService;
use App\Services\PrayerTimeService;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $prayer = app(PrayerTimeService::class)->status();
        $hijri  = app(HijriService::class);

        $income  = (float) Transaction::approved()->income()->whereYear('date', now()->year)->sum('amount');
        $expense = (float) Transaction::approved()->expense()->whereYear('date', now()->year)->sum('amount');

        return view('livewire.pub.home', [
            'prayer'     => $prayer,
            'hijri'      => $hijri->convert(),
            'holiday'    => $hijri->holiday(),
            'banner'     => Banner::active()->where('position', 'hero')->first(),
            'running'    => RunningText::active()->pluck('text'),
            'todayKajian' => Kajian::published()->whereDate('start_at', today())->orderBy('start_at')->get(),
            'kajians'    => Kajian::published()->upcoming()->take(3)->get(),
            'jumat'      => JumatSchedule::upcoming()->first(),
            'campaigns'  => Campaign::active()->orderByDesc('is_featured')->orderByDesc('collected')->take(3)->get(),
            'articles'   => Article::published()->with('category')->latest('published_at')->take(3)->get(),
            'programs'   => Program::active()->take(6)->get(),
            'events'     => Event::where('is_public', true)->upcoming()->take(4)->get(),
            'gallery'    => Gallery::where('is_published', true)->with('photos')->latest('taken_at')->first(),
            'live'       => Livestream::whereIn('status', ['live', 'scheduled'])->orderByDesc('status')->first(),
            'umkm'       => UmkmBusiness::approved()->latest()->take(4)->get(),
            'income'     => $income,
            'expense'    => $expense,
            'balance'    => $income - $expense,
        ])->layout('components.layouts.public', ['hero' => true]);
    }
}
