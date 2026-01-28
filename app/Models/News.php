<?php

namespace App\Models;

use App\Enums\NewStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
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


    /**
     * Acciones al crear/actualizar/eliminar modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });

        // Sustituir el orden en lugar de desplazar
        static::saving(function ($news) {
            if ($news->isDirty('sort_order')) {
                $newOrder = $news->sort_order;

                if ($newOrder >= 1 && $newOrder <= 5) {
                    self::where('sort_order', $newOrder)
                        ->where('id', '!=', $news->id)
                        ->update(['sort_order' => 0]);
                }
            }
        });

        // Opcional: Limpieza en 'saved' para asegurar que no existan duplicados por error
        static::saved(function ($news) {
            if ($news->isDirty('sort_order') && $news->sort_order > 0) {
                self::where('sort_order', $news->sort_order)
                    ->where('id', '!=', $news->id)
                    ->update(['sort_order' => 0]);
            }
        });

        // Borra la imagen destacada al eliminar la noticia
        static::deleted(function ($model) {
            if ($model->featured_image) {
                Storage::disk('public')->delete($model->featured_image);
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


    public function scopeInMainOrder($query)
    {
        return $query->published()
            ->whereBetween('sort_order', [1, 5])
            ->orderBy('sort_order', 'asc');
    }

    // Scope para traer siempre las relaciones comunes y evitar el problema N+1
    public function scopeWithStandardRelations($query)
    {
        return $query->with(['category', 'user', 'images', 'videos']);
    }
}
