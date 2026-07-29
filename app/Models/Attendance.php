<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = [];
    protected $casts = ['checked_in_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function attendable() { return $this->morphTo(); }
}
