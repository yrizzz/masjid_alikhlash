<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $guarded = [];
    protected $casts = ['amount' => 'decimal:2', 'date' => 'date'];

    public function account() { return $this->belongsTo(FinanceAccount::class, 'finance_account_id'); }
    public function category() { return $this->belongsTo(Category::class); }
    public function donation() { return $this->belongsTo(Donation::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function scopeApproved($q) { return $q->where('status', 'approved'); }
    public function scopeIncome($q) { return $q->where('type', 'in'); }
    public function scopeExpense($q) { return $q->where('type', 'out'); }

    protected static function booted(): void
    {
        static::creating(function (self $t) {
            $t->code ??= strtoupper($t->type === 'in' ? 'TRX-IN-' : 'TRX-OUT-').now()->format('ymd').'-'.Str::upper(Str::random(4));
        });
    }
}
