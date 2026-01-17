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
            // 1. Solo actuamos si el sort_order ha cambiado
            if ($news->isDirty('sort_order')) {
                $oldOrder = $news->getOriginal('sort_order');
                $newOrder = $news->sort_order;

                // Caso A: Si el usuario asigna un orden entre 1 y 5
                if ($newOrder >= 1 && $newOrder <= 5) {
                    // Desplazamos las que están en el camino para no perder ninguna
                    self::where('sort_order', '>=', $newOrder)
                        ->where('id', '!=', $news->id)
                        ->increment('sort_order');
                }

                // Caso B: Si estamos moviendo una que ya tenía orden (ej. de 3 a 5)
                // O si simplemente queremos asegurar que no queden huecos después de cualquier cambio
            }
        });

        static::saved(function ($news) {
            // IMPORTANTE: Usamos 'saved' (después de guardar) para re-normalizar la lista
            if ($news->isDirty('sort_order')) {
                // 1. Obtenemos todas las noticias que tienen algún orden (del 1 en adelante)
                $allOrdered = self::where('sort_order', '>', 0)
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('updated_at', 'desc') // En caso de empate, la más reciente manda
                    ->get();

                // 2. Re-asignamos los números estrictamente del 1 al 5
                foreach ($allOrdered as $index => $item) {
                    $correctOrder = $index + 1;

                    if ($correctOrder > 5) {
                        // Si se pasa de 5, vuelve a ser 0
                        if ($item->sort_order !== 0) {
                            $item->updateQuietly(['sort_order' => 0]);
                        }
                    } else {
                        // Si el orden no es el correcto (ej. hay un hueco), lo corregimos
                        if ($item->sort_order !== $correctOrder) {
                            $item->updateQuietly(['sort_order' => $correctOrder]);
                        }
                    }
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
