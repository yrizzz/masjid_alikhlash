<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KajianRegistration extends Model
{
    protected $guarded = [];
    protected $casts = ['checked_in_at' => 'datetime'];

    public function kajian() { return $this->belongsTo(Kajian::class); }
    public function user() { return $this->belongsTo(User::class); }

    protected static function booted(): void
    {
        static::creating(function ($m) {
            $m->code ??= 'KJ-'.Str::upper(Str::random(8));
        });
    }
}
