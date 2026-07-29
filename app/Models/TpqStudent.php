<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpqStudent extends Model
{
    protected $guarded = [];
    protected $casts = ['birth_date' => 'date', 'joined_at' => 'date'];

    public function tpqClass() { return $this->belongsTo(TpqClass::class, 'tpq_class_id'); }
    public function attendances() { return $this->hasMany(TpqAttendance::class); }
    public function grades() { return $this->hasMany(TpqGrade::class); }
    public function payments() { return $this->hasMany(TpqPayment::class); }

    public function getAttendanceRateAttribute(): float
    {
        $total = $this->attendances()->count();
        return $total ? round($this->attendances()->where('status', 'hadir')->count() / $total * 100) : 0;
    }
}
