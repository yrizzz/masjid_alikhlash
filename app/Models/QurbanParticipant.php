<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class QurbanParticipant extends Model
{
    protected $guarded = [];
    protected $casts = ['amount' => 'decimal:2', 'paid' => 'decimal:2'];

    public function animal() { return $this->belongsTo(QurbanAnimal::class, 'qurban_animal_id'); }
    public function user() { return $this->belongsTo(User::class); }

    protected static function booted(): void
    {
        static::creating(fn (self $m) => $m->code ??= 'QR-'.now()->format('y').'-'.Str::upper(Str::random(5)));
    }
}
