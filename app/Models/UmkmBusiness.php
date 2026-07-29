<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkmBusiness extends Model
{
    protected $guarded = [];
    protected $casts = ['lat' => 'float', 'lng' => 'float', 'is_featured' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function user() { return $this->belongsTo(User::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function products() { return $this->hasMany(UmkmProduct::class); }

    public function scopeApproved($q) { return $q->where('status', 'approved'); }

    public function getWaLinkAttribute(): ?string
    {
        if (! $this->whatsapp) return null;
        $n = preg_replace('/\D/', '', $this->whatsapp);
        $n = str_starts_with($n, '0') ? '62'.substr($n, 1) : $n;

        return "https://wa.me/{$n}";
    }
}
