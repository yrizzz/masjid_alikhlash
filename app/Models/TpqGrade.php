<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpqGrade extends Model
{
    protected $guarded = [];

    public function student() { return $this->belongsTo(TpqStudent::class, 'tpq_student_id'); }
}
