<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ZakatPayment extends Model
{
    protected $guarded = [];
    protected $casts = ['base_amount' => 'decimal:2', 'amount' => 'decimal:2'];

    public const TYPES = ['fitrah' => 'Zakat Fitrah', 'maal' => 'Zakat Maal', 'profesi' => 'Zakat Profesi', 'emas' => 'Zakat Emas & Perak', 'perdagangan' => 'Zakat Perdagangan'];

    public function user() { return $this->belongsTo(User::class); }
    public function donation() { return $this->belongsTo(Donation::class); }

    protected static function booted(): void
    {
        static::creating(fn (self $m) => $m->code ??= 'ZK-'.now()->format('ymd').'-'.Str::upper(Str::random(4)));
    }
}
