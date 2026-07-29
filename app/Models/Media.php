<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $guarded = [];

    public function user() { return $this->belongsTo(User::class); }

    public function getUrlAttribute(): string { return asset('storage/'.ltrim($this->path, '/')); }

    public function getHumanSizeAttribute(): string
    {
        $b = (int) $this->size;
        foreach (['B', 'KB', 'MB', 'GB'] as $u) {
            if ($b < 1024) return round($b, 1).' '.$u;
            $b /= 1024;
        }
        return round($b, 1).' TB';
    }
}
