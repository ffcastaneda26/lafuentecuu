<?php

namespace App\Services;

use App\Models\News;
use App\Models\Category;
use Illuminate\Support\Collection;

class NewsService
{
    /**
     * Obtiene las noticias destacadas (Top 5)
     */
    public function getFeatured(): Collection
    {
        return News::withStandardRelations()
            ->inMainOrder()
            ->get();
    }

    /**
     * Una noticia por categoría para la sección "Más Noticias"
     */
    public function getMoreNews(Collection $excludeIds): Collection
    {
        return Category::active()
            ->with(['news' => function ($query) use ($excludeIds) {
                $query->published()
                    ->where('is_more_news', true)
                    ->whereNotIn('id', $excludeIds)
                    ->latest('published_at')
                    ->take(1);
            }])
            ->get()
            ->pluck('news')
            ->flatten();
    }

    /**
     * Noticias más vistas
     */
    public function getMostViewed(int $limit = 5): Collection
    {
        return News::withStandardRelations()
            ->where('is_most_viewed', true)
            ->latest('published_at')
            ->orderBy('views_count', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * Listado general de noticias recientes
     */
    public function getRecent(int $limit = 20): Collection
    {
        return News::withStandardRelations()
            ->orderByRaw('CASE WHEN sort_order > 0 THEN 0 ELSE 1 END')
            ->orderBy('sort_order', 'asc')
            ->orderBy('featured', 'desc')
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}
