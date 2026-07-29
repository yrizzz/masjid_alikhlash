<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Donation extends Model
{
    protected $guarded = [];
    protected $casts = ['amount' => 'decimal:2', 'is_anonymous' => 'boolean', 'paid_at' => 'datetime'];

    public const TYPES = ['infaq' => 'Infaq & Sedekah', 'zakat' => 'Zakat', 'qurban' => 'Qurban', 'wakaf' => 'Wakaf', 'kotak-amal' => 'Kotak Amal'];

    public function campaign() { return $this->belongsTo(Campaign::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function channel() { return $this->belongsTo(PaymentChannel::class, 'payment_channel_id'); }

    public function scopePaid($q) { return $q->where('status', 'paid'); }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Hamba Allah' : $this->name;
    }

    protected static function booted(): void
    {
        static::creating(function (self $d) {
            $d->code ??= 'DN'.now()->format('ymd').Str::upper(Str::random(5));
        });
    }
}
