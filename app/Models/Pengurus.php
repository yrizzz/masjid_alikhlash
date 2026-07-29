<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    protected $table = 'pengurus';
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }

    public function scopeActive($q) { return $q->where('is_active', true)->orderBy('order'); }

    public function getPeriodAttribute(): string
    {
        return $this->period_start && $this->period_end ? "{$this->period_start}–{$this->period_end}" : '—';
    }
}
