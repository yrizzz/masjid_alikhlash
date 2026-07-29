<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImamSchedule extends Model
{
    protected $guarded = [];

    public const DAYS = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    public const PRAYERS = ['subuh' => 'Subuh', 'dzuhur' => 'Dzuhur', 'ashar' => 'Ashar', 'maghrib' => 'Maghrib', 'isya' => 'Isya'];

    public function getDayNameAttribute(): string { return self::DAYS[$this->day_of_week] ?? '—'; }
    public function getPrayerNameAttribute(): string { return self::PRAYERS[$this->prayer] ?? $this->prayer; }
}
