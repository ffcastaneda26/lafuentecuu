<?php

namespace App\Http\Controllers;

use App\Enums\AdvertisementPositionEnum;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\News;
use App\Models\Sponsor;

class HomeController extends Controller
{
    /**
     * Muestra la página principal del portal de noticias
     */
    public function index()
    {
        // 1. Mantenimiento: Desactivar anuncios caducados
        Advertisement::where('active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay())
            ->update(['active' => false]);

        // 2. Obtener Anuncios Reales para el encabezado
        $realAds = Advertisement::active()
            ->where('position', AdvertisementPositionEnum::HEADER->value)
            ->orderBy('priority', 'asc')
            ->get(['id', 'title', 'click_url', 'media_url', 'type', 'sponsor_id']);

        // 3. Obtener Patrocinadores activos que NO aparecen en los anuncios anteriores
        // Esto asegura que si un patrocinador ya tiene un anuncio real, no se duplique con su logo básico.
        $sponsorsWithoutAds = Sponsor::active()
            ->whereDate('contract_start', '<=', now())
            ->whereDate('contract_end', '>=', now())
            ->whereNotIn('id', $realAds->pluck('sponsor_id'))
            ->get();

        $placeholderAds = $sponsorsWithoutAds->map(function ($sponsor) {
            return (object) [
                'id' => null,
                'title' => $sponsor->name,
                'click_url' => $sponsor->website,
                'media_url' => $sponsor->logo,
                'type' => 'imagen',
                'is_placeholder' => true // Etiqueta para identificarlo en la vista
            ];
        });

        // 5. Unificar ambas colecciones para la vista
        $headerAds = $realAds->concat($placeholderAds);

        // Obtener categorías activas ordenadas
        $categories = Category::active()
            ->ordered()
            ->get();
        // 1. Top 5 Ordenado (Cabecera)
        $featuredNews = News::published()
            ->whereBetween('sort_order', [1, 5])
            ->orderBy('sort_order', 'asc')
            ->with(['category', 'user', 'images', 'videos'])
            ->get();
        // 2. Obtener "Más Noticias": 2 más recientes por categoría
        // Filtramos en la base de datos y luego limitamos con PHP para mayor precisión
        $moreNews = News::published()
            ->where('is_more_news', true)
            ->whereNotIn('id', $featuredNews->pluck('id'))
            ->orderBy('published_at', 'desc')
            ->orderBy('category_id')
            ->get()
            ->groupBy('category_id') // Agrupamos por categoría
            ->map(function ($categoryGroup) {
                return $categoryGroup->take(1); // Tomamos solo la noticia mas  recientes de ese grupo
            })
            ->flatten();
        $mostViewedNews = News::published()
            ->with(['category', 'user', 'images', 'videos'])
            ->where('is_most_viewed', true)
            ->orderby('published_at', 'desc')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        // Obtener noticias publicadas con sus relaciones
        $news = News::published()
            ->with(['category', 'user', 'images', 'videos'])
            ->orderByRaw('CASE WHEN sort_order > 0 THEN 0 ELSE 1 END') // Primero las que tienen orden
            ->orderBy('sort_order', 'asc') // 1, 2, 3...
            ->orderBy('featured', 'desc')  // Luego las destacadas genéricas
            ->orderBy('published_at', 'desc') // Finalmente por fecha
            ->take(20)
            ->get();

        // Obtener información de contacto
        $contactInfo = ContactInfo::first() ?? new ContactInfo;

        return view('home', compact(
            'headerAds', // Pasamos los anuncios en lugar de sponsors
            'categories',
            'featuredNews',
            'moreNews',
            'news',
            'mostViewedNews',
            'contactInfo'
        ));
    }

    /**
     * Muestra noticias por categoría
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $categories = Category::active()
            ->ordered()
            ->get();

        // Obtener noticias de esta categoría
        $news = News::published()
            ->where('category_id', $category->id)
            ->with(['category', 'user', 'images', 'videos'])
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $sponsors = Sponsor::active()
            ->whereDate('contract_start', '<=', now())
            ->whereDate('contract_end', '>=', now())
            ->inRandomOrder()
            ->with('advertisements')
            ->get();

        $contactInfo = ContactInfo::first() ?? new ContactInfo;

        return view('category', compact(
            'category',
            'categories',
            'news',
            'sponsors',
            'contactInfo'
        ));
    }

    /**
     * Muestra el detalle de una noticia
     */
    public function show($slug)
    {
        $news = News::published()
            ->where('slug', $slug)
            ->with(['category', 'user', 'images', 'videos', 'socialLinks'])
            ->firstOrFail();

        $news->increment('views_count');

        $categories = Category::active()
            ->ordered()
            ->get();

        $relatedNews = News::published()
            ->where('category_id', $news->category_id)
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        $sponsors = Sponsor::active()
            ->whereDate('contract_start', '<=', now())
            ->whereDate('contract_end', '>=', now())
            ->inRandomOrder()
            ->with('advertisements')
            ->take(2)
            ->get();

        $contactInfo = ContactInfo::first() ?? new ContactInfo;

        return view('news.show', compact(
            'news',
            'categories',
            'relatedNews',
            'sponsors',
            'contactInfo'
        ));
    }
}
