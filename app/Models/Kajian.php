<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Kajian extends Model
{
    protected $guarded = [];
    protected $casts = [
        'start_at' => 'datetime', 'end_at' => 'datetime',
        'is_published' => 'boolean', 'open_registration' => 'boolean',
    ];

    public function getRouteKeyName(): string { return 'slug'; }

    public function category() { return $this->belongsTo(Category::class); }
    public function registrations() { return $this->hasMany(KajianRegistration::class); }
    public function tags() { return $this->morphToMany(Tag::class, 'taggable'); }
    public function bookmarks(): MorphMany { return $this->morphMany(Bookmark::class, 'bookmarkable'); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeUpcoming($q) { return $q->whereNotNull('start_at')->where('start_at', '>=', now()->startOfDay())->orderBy('start_at'); }

    public function getYoutubeIdAttribute(): ?string
    {
        if (! $this->media_url) return null;
        preg_match('~(?:youtu\.be/|v=|embed/|shorts/)([A-Za-z0-9_-]{11})~', $this->media_url, $m);
        return $m[1] ?? null;
    }

    public function getIsTodayAttribute(): bool
    {
        return $this->start_at && $this->start_at->isToday();
    }
}
