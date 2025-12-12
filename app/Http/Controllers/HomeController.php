<?php

namespace App\Http\Controllers;

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
        // Obtener categorías activas ordenadas
        $categories = Category::active()
            ->ordered()
            ->get();

        // Obtener noticias publicadas con sus relaciones
        $news = News::published()
            ->with(['category', 'user', 'images', 'videos'])
            ->orderBy('featured', 'desc')
            ->orderBy('updated_at', 'desc')
            ->orderBy('published_at', 'desc')

            ->take(20) // Limitamos a las últimas 20 noticias
            ->get();

        // Obtener patrocinadores activos
        $sponsors = Sponsor::active()
            ->whereDate('contract_start', '<=', now())
            ->whereDate('contract_end', '>=', now())
            ->inRandomOrder() // Rotar patrocinadores aleatoriamente
            ->get();

        // Obtener información de contacto
        $contactInfo = ContactInfo::first() ?? new ContactInfo;

        return view('home', compact(
            'categories',
            'news',
            'sponsors',
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
