<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livestream extends Model
{
    protected $guarded = [];
    protected $casts = ['start_at' => 'datetime'];

    public const PLATFORMS = [
        'youtube'   => ['label' => 'YouTube',   'icon' => 'youtube'],
        'facebook'  => ['label' => 'Facebook',  'icon' => 'facebook'],
        'instagram' => ['label' => 'Instagram', 'icon' => 'instagram'],
        'tiktok'    => ['label' => 'TikTok',    'icon' => 'music'],
    ];

    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->platform !== 'youtube') return null;
        $id = $this->embed_id;
        if (! $id && $this->url) {
            preg_match('~(?:youtu\.be/|v=|embed/|live/)([A-Za-z0-9_-]{11})~', $this->url, $m);
            $id = $m[1] ?? null;
        }
        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }
}
