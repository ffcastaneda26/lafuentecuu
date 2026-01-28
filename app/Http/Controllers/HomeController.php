<?php

namespace App\Http\Controllers;

use App\Enums\AdvertisementPositionEnum;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\News;
use App\Models\Sponsor;

use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Muestra la página principal del portal de noticias
     */
    public function index()
    {

        $headerAds = $this->getAvailableAds();
        $categories = $this->getActiveCategories();
        $contactInfo = ContactInfo::first() ?? new ContactInfo;

        $featuredNews = News::withStandardRelations()
            ->inMainOrder()
            ->get();
        $moreNews = Category::active()
            ->with(['news' => function ($query) use ($featuredNews) {
                $query->published()
                    ->where('is_more_news', true)
                    ->whereNotIn('id', $featuredNews->pluck('id'))
                    ->latest('published_at')
                    ->take(1); // La base de datos solo entrega UNA por categoría
            }])
            ->get()
            ->pluck('news') // Extraemos las colecciones de noticias
            ->flatten();

        $mostViewedNews = News::withStandardRelations()
            ->where('is_most_viewed', true)
            ->latest('published_at')
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();

        $news = News::withStandardRelations()
            ->orderByRaw('CASE WHEN sort_order > 0 THEN 0 ELSE 1 END')
            ->orderBy('sort_order', 'asc')
            ->orderBy('featured', 'desc')
            ->latest('published_at')
            ->take(20)
            ->get();
        // --------------------------



        return view('home', compact(
            'headerAds',
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

        $categoryAds = $this->getAvailableAds();
        $categories = $this->getActiveCategories();

        $news = News::published()
            ->where('category_id', $category->id)
            ->withStandardRelations() // Reutilizamos relaciones
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $contactInfo = $this->getContactInfo();

        return view('category', compact(
            'category',
            'categoryAds',
            'categories',
            'news',
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



        $contactInfo = ContactInfo::first() ?? new ContactInfo;

        return view('news.show', compact(
            'news',
            'categories',
            'relatedNews',
            'contactInfo'
        ));
    }

    private function getAvailableAds(): Collection
    {
        // Actualiza anunicos que ya no están activos
        Advertisement::where('active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', now()->startOfDay())
            ->update(['active' => false]);

        //  Regresa los anuncios disponibles para la cabecera
        return Advertisement::active()
            ->where('position', AdvertisementPositionEnum::HEADER->value)
            ->orderBy('priority', 'asc')
            ->get(['id', 'title', 'click_url', 'media_url', 'type', 'sponsor_id']);
    }

    private function getActiveCategories(): Collection
    {
        return Category::active()
            ->ordered()
            ->get();
    }

    private function getContactInfo(): ContactInfo
    {
        return ContactInfo::first() ?? new ContactInfo;
    }
}
