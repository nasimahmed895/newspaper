<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'image_credit',
        'image_source',
        'author',
        'source',
        'source_url',
        'trending_topic',
        'is_published',
        'published_at',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'reading_time_minutes',
        'view_count',
        'is_trending',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'is_trending' => 'boolean',
            'published_at' => 'datetime',
            'view_count' => 'integer',
            'reading_time_minutes' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('published_at', '>=', now()->subDays($days));
    }

    public function getExcerptAttribute($value): string
    {
        if ($value) {
            return $value;
        }

        return Str::limit(strip_tags($this->content), 200);
    }

    public function getReadingTimeMinutesAttribute($value): int
    {
        if ($value > 0) {
            return $value;
        }

        $words = str_word_count(strip_tags($this->content ?? ''));
        return (int) ceil($words / 200);
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });

        static::updated(function (Article $article) {
            if ($article->isDirty('is_published') && $article->is_published && !$article->published_at) {
                $article->published_at = now();
            }
        });
    }
}
