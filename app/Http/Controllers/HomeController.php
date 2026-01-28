<?php

namespace App\Http\Controllers;

use App\Enums\AdvertisementPositionEnum;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\ContactInfo;
use App\Models\News;
use App\Models\Sponsor;
use App\Services\NewsService;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Muestra la página principal del portal de noticias
     */
    public function index()
    {
        $featuredNews = $this->newsService->getFeatured();
        $moreNews = $this->newsService->getMoreNews(
            excludeIds: $featuredNews->pluck('id')
        );
        $mostViewedNews = $this->newsService->getMostViewed(5);
        $news = $this->newsService->getRecent(20);

        $categories = Category::active()->ordered()->get();
        $contactInfo = ContactInfo::first() ?? new ContactInfo;

        return view('home', compact(
            'featuredNews',
            'moreNews',
            'mostViewedNews',
            'news',
            'categories',
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
