<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InventoryLoan extends Model
{
    protected $guarded = [];
    protected $casts = ['borrow_date' => 'date', 'due_date' => 'date', 'returned_at' => 'date'];

    public function inventory() { return $this->belongsTo(Inventory::class); }
    public function user() { return $this->belongsTo(User::class); }

    protected static function booted(): void
    {
        static::creating(fn (self $m) => $m->code ??= 'PJM-'.now()->format('ymd').'-'.Str::upper(Str::random(4)));
    }
}
