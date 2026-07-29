<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpqPayment extends Model
{
    protected $guarded = [];
    protected $casts = ['amount' => 'decimal:2', 'paid_at' => 'date'];

    public function student() { return $this->belongsTo(TpqStudent::class, 'tpq_student_id'); }
}
