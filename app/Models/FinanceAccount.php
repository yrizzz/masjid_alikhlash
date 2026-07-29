<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceAccount extends Model
{
    protected $guarded = [];
    protected $casts = ['opening_balance' => 'decimal:2', 'is_active' => 'boolean'];

    public function transactions() { return $this->hasMany(Transaction::class); }

    public function getBalanceAttribute(): float
    {
        $in = (float) $this->transactions()->approved()->where('type', 'in')->sum('amount');
        $out = (float) $this->transactions()->approved()->where('type', 'out')->sum('amount');

        return (float) $this->opening_balance + $in - $out;
    }
}
