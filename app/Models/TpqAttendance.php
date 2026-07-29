<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpqAttendance extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date'];

    public function student() { return $this->belongsTo(TpqStudent::class, 'tpq_student_id'); }
}
