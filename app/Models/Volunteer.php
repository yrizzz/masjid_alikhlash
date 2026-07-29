<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $guarded = [];
    protected $casts = ['interests' => 'array'];

    public function user() { return $this->belongsTo(User::class); }
    public function assignments() { return $this->hasMany(VolunteerAssignment::class); }
}
