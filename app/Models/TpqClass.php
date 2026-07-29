<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpqClass extends Model
{
    protected $guarded = [];
    protected $casts = ['fee' => 'decimal:2', 'is_active' => 'boolean'];

    public function students() { return $this->hasMany(TpqStudent::class); }
    public function teacher() { return $this->belongsTo(User::class, 'user_id'); }
}
