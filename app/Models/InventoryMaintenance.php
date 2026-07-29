<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMaintenance extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date', 'next_due' => 'date', 'cost' => 'decimal:2'];

    public function inventory() { return $this->belongsTo(Inventory::class); }
}
