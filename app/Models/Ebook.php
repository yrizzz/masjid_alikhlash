<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    protected $guarded = [];
    protected $casts = ['is_published' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function category() { return $this->belongsTo(Category::class); }
}
