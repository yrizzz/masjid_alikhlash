<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $guarded = [];
    protected $casts = ['published_at' => 'datetime', 'is_featured' => 'boolean'];

    public function getRouteKeyName(): string { return 'slug'; }

    public function category() { return $this->belongsTo(Category::class); }
    public function author() { return $this->belongsTo(User::class, 'user_id'); }
    public function tags() { return $this->morphToMany(Tag::class, 'taggable'); }
    public function comments() { return $this->morphMany(Comment::class, 'commentable'); }
    public function bookmarks() { return $this->morphMany(Bookmark::class, 'bookmarkable'); }

    public function scopePublished($q) { return $q->whereNotNull('published_at')->where('published_at', '<=', now()); }

    protected static function booted(): void
    {
        static::saving(function (self $a) {
            $words = str_word_count(strip_tags((string) $a->body));
            $a->reading_time = max(1, (int) ceil($words / 200));
            if (! $a->excerpt) $a->excerpt = Str::limit(strip_tags((string) $a->body), 160);
        });
    }
}
