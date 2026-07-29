<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkmProduct extends Model
{
    protected $guarded = [];
    protected $casts = ['price' => 'decimal:2', 'is_available' => 'boolean'];

    public function business() { return $this->belongsTo(UmkmBusiness::class, 'umkm_business_id'); }
}
