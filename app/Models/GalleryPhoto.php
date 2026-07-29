<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $guarded = [];

    public function gallery() { return $this->belongsTo(Gallery::class); }
}
