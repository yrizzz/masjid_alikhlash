<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $guarded = [];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_featured' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function assignments() { return $this->hasMany(VolunteerAssignment::class); }

    public function scopeActive($q) { return $q->where('status', 'active')->orderBy('order'); }
}
