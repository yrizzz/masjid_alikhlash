<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];
    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime', 'all_day' => 'boolean', 'is_public' => 'boolean'];

    public const TYPES = [
        'kajian'         => ['label' => 'Kajian',        'color' => '#2563eb'],
        'rapat'          => ['label' => 'Rapat',         'color' => '#7c3aed'],
        'tpq'            => ['label' => 'TPQ',           'color' => '#059669'],
        'kerja-bakti'    => ['label' => 'Kerja Bakti',   'color' => '#ea580c'],
        'hari-besar'     => ['label' => 'Hari Besar Islam', 'color' => '#0d9488'],
        'libur-nasional' => ['label' => 'Libur Nasional','color' => '#dc2626'],
        'pengajian'      => ['label' => 'Pengajian',     'color' => '#4f46e5'],
        'agenda'         => ['label' => 'Agenda',        'color' => '#64748b'],
    ];

    public function getTypeLabelAttribute(): string { return self::TYPES[$this->type]['label'] ?? ucfirst($this->type); }
    public function getTypeColorAttribute(): string { return $this->color ?: (self::TYPES[$this->type]['color'] ?? '#64748b'); }

    public function scopeUpcoming($q) { return $q->where('start_at', '>=', now()->startOfDay())->orderBy('start_at'); }
}
