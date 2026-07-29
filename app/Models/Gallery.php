<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $guarded = [];
    protected $casts = ['taken_at' => 'date', 'is_published' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function category() { return $this->belongsTo(Category::class); }
    public function photos() { return $this->hasMany(GalleryPhoto::class)->orderBy('order'); }
}
