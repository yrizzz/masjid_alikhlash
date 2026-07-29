<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $guarded = [];
    protected $casts = ['purchase_date' => 'date', 'price' => 'decimal:2', 'is_lendable' => 'boolean'];

    public const CONDITIONS = ['baik' => 'Baik', 'rusak-ringan' => 'Rusak Ringan', 'rusak-berat' => 'Rusak Berat', 'hilang' => 'Hilang'];

    public function category() { return $this->belongsTo(Category::class); }
    public function maintenances() { return $this->hasMany(InventoryMaintenance::class)->latest('date'); }
    public function loans() { return $this->hasMany(InventoryLoan::class); }

    public function getAgeAttribute(): ?string
    {
        if (! $this->purchase_date) return null;
        $y = (int) $this->purchase_date->diffInYears(now());
        $m = (int) $this->purchase_date->diffInMonths(now()) % 12;

        return $y > 0 ? "{$y} tahun".($m ? " {$m} bln" : '') : "{$m} bulan";
    }
}
