<?php

namespace App\Models;

use App\Enums\NewStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'subtitle',
        'body',
        'slug',
        'featured_image',
        'status',
        'featured',
        'published_at',
        'views_count',
        'category_id',
        'user_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'status' => NewStatusEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(NewsImage::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(NewsVideo::class);
    }

    public function socialLinks(): HasMany
    {
        return $this->hasMany(NewsSocialLink::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'publicada')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query, $limit = 4)
    {
        return $query->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit);
    }
}
