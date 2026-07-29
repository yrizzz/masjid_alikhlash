<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $guarded = [];
    protected $casts = ['fee' => 'decimal:2', 'is_active' => 'boolean', 'facilities' => 'array'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function bookings() { return $this->hasMany(RoomBooking::class); }
}
