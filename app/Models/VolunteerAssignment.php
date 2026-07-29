<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VolunteerAssignment extends Model
{
    protected $guarded = [];
    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime'];

    public function volunteer() { return $this->belongsTo(Volunteer::class); }
    public function program() { return $this->belongsTo(Program::class); }
}
