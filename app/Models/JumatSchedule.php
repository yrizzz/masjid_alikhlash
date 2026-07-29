<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JumatSchedule extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date'];

    public function scopeUpcoming($q) { return $q->whereDate('date', '>=', today())->orderBy('date'); }
}
