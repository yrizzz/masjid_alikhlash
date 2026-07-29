<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RoomBooking extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date'];

    public function room() { return $this->belongsTo(Room::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function scopeApproved($q) { return $q->where('status', 'approved'); }

    protected static function booted(): void
    {
        static::creating(fn (self $m) => $m->code ??= 'BK-'.now()->format('ymd').'-'.Str::upper(Str::random(4)));
    }
}
