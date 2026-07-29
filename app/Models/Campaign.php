<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = [];
    protected $casts = [
        'target' => 'decimal:2', 'collected' => 'decimal:2',
        'start_date' => 'date', 'deadline' => 'date', 'is_featured' => 'boolean',
    ];

    public function getRouteKeyName(): string { return 'slug'; }

    public function category() { return $this->belongsTo(Category::class); }
    public function donations() { return $this->hasMany(Donation::class); }
    public function updates() { return $this->hasMany(CampaignUpdate::class)->latest(); }

    public function scopeActive($q) { return $q->where('status', 'active'); }

    public function getProgressAttribute(): float
    {
        return $this->target > 0 ? min(100, round($this->collected / $this->target * 100, 1)) : 0;
    }

    public function getDaysLeftAttribute(): ?int
    {
        return $this->deadline ? max(0, (int) today()->diffInDays($this->deadline, false)) : null;
    }

    public function getDonorCountAttribute(): int
    {
        return $this->donations()->where('status', 'paid')->count();
    }

    public function recalculate(): void
    {
        $this->update(['collected' => $this->donations()->where('status', 'paid')->sum('amount')]);
    }
}
