<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanAnimal extends Model
{
    protected $guarded = [];
    protected $casts = ['price_per_slot' => 'decimal:2'];

    public function participants() { return $this->hasMany(QurbanParticipant::class); }

    public function getSlotsLeftAttribute(): int { return max(0, $this->slots - $this->slots_taken); }
    public function getProgressAttribute(): float { return $this->slots > 0 ? round($this->slots_taken / $this->slots * 100) : 0; }
}
