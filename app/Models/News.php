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
        'sort_order',
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

        // Nueva lógica de reclasificación
        static::saving(function ($news) {
            // Solo actuamos si el sort_order es mayor a 0 y ha cambiado
            if ($news->sort_order > 0 && $news->isDirty('sort_order')) {

                // Buscamos si ya existe una noticia con ese orden
                $existing = self::where('sort_order', $news->sort_order)
                    ->where('id', '!=', $news->id)
                    ->first();

                if ($existing) {
                    // Desplazamos todas las noticias hacia abajo (incrementamos su orden)
                    // de la posición actual en adelante
                    self::where('sort_order', '>=', $news->sort_order)
                        ->where('id', '!=', $news->id)
                        ->increment('sort_order');
                }
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
