<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public function scopeType($q, string $type)
    {
        return $q->where('type', $type)->orderBy('order');
    }

    public function articles() { return $this->hasMany(Article::class); }
    public function kajians() { return $this->hasMany(Kajian::class); }
}
