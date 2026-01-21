@extends('layouts.main')

@section('title', 'Inicio - Portal de Noticias')

@section('content')
    <div class="space-y-6">
        <!-- Banner de Anuncios (Horizontal) -->


        @if ($headerAds->isNotEmpty())
            <div class="relative group bg-gray-50 rounded-xl shadow-inner p-2 mb-6">
                <div id="ad-slider"
                    class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar gap-4 h-44 items-center px-4">
                    @foreach ($headerAds as $ad)
                        <div class="ad-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 snap-center h-full">
                            <div
                                class="relative h-full w-full bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm">

                                {{-- Etiqueta de Patrocinador --}}
                                @if (isset($ad->is_placeholder) && $ad->is_placeholder)
                                    <div class="absolute top-2 right-2 z-10">
                                        <span
                                            class="bg-gray-800/80 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                                            Patrocinador
                                        </span>
                                    </div>
                                @endif

                                <a href="{{ $ad->click_url }}" target="_blank" class="block h-full w-full">
                                    @php
                                        // Soporte para Enum de Advertisement o string de Placeholder
                                        $isVideo =
                                            (isset($ad->type->value) && $ad->type->value === 'video') ||
                                            $ad->type === 'video';
                                    @endphp

                                    @if ($isVideo)
                                        <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                                            <source src="{{ Storage::url($ad->media_url) }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ Storage::url($ad->media_url) }}" alt="{{ $ad->title }}"
                                            class="w-full h-full object-contain p-3 transition-transform duration-500 hover:scale-105">
                                    @endif
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button onclick="scrollAds('left')"
                    class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-md ml-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>

                <button onclick="scrollAds('right')"
                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-md mr-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <script>
                const slider = document.getElementById('ad-slider');
                let autoScrollTimer;

                function scrollAds(direction) {
                    // Reiniciar el temporizador al hacer clic manual
                    resetAutoScroll();

                    const scrollAmount = slider.offsetWidth / (window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1));
                    const maxScroll = slider.scrollWidth - slider.clientWidth;

                    if (direction === 'right') {
                        // Si estamos al final, volver al inicio
                        if (slider.scrollLeft >= maxScroll - 10) {
                            slider.scrollTo({
                                left: 0,
                                behavior: 'smooth'
                            });
                        } else {
                            slider.scrollBy({
                                left: scrollAmount,
                                behavior: 'smooth'
                            });
                        }
                    } else {
                        // Si estamos al inicio, ir al final
                        if (slider.scrollLeft <= 10) {
                            slider.scrollTo({
                                left: maxScroll,
                                behavior: 'smooth'
                            });
                        } else {
                            slider.scrollBy({
                                left: -scrollAmount,
                                behavior: 'smooth'
                            });
                        }
                    }
                }

                function startAutoScroll() {
                    autoScrollTimer = setInterval(() => {
                        scrollAds('right');
                    }, 3000); // 10 segundos
                }

                function resetAutoScroll() {
                    clearInterval(autoScrollTimer);
                    startAutoScroll();
                }

                // Iniciar al cargar
                document.addEventListener('DOMContentLoaded', startAutoScroll);
            </script>

            <style>
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }

                .no-scrollbar {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            </style>
        @endif
        <!-- Grid Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">
                <!-- Noticia Principal (Featured) -->
                @if ($news->isNotEmpty())
                    @php $featuredNews = $news->first(); @endphp
                    <article class="bg-white rounded-lg shadow-md overflow-hidden group cursor-pointer">
                        <a href="/noticia/{{ $featuredNews->slug }}">
                            <div class="relative overflow-hidden h-96">
                                <img src="{{ Storage::url($featuredNews->featured_image) }}"
                                    alt="{{ $featuredNews->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div
                                    class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/70 to-transparent p-6">
                                    <!-- Categoría -->
                                    <span
                                        class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 rounded mb-2 uppercase">
                                        {{ $featuredNews->category->name }}
                                    </span>
                                    <!-- Título -->
                                    <h1 class="text-white text-3xl font-bold mb-2 line-clamp-2">
                                        {{ $featuredNews->title }}
                                    </h1>
                                    <!-- Subtítulo -->
                                    @if ($featuredNews->subtitle)
                                        <p class="text-gray-200 text-sm line-clamp-2">
                                            {{ $featuredNews->subtitle }}
                                        </p>
                                    @endif
                                    <!-- Metadata -->
                                    <div class="flex items-center gap-4 mt-3 text-gray-300 text-xs">
                                        <span><i
                                                class="far fa-clock mr-1"></i>{{ $featuredNews->published_at->diffForHumans() }}</span>
                                        {{-- <span><i
                                                class="far fa-eye mr-1"></i>{{ number_format($featuredNews->views_count) }}
                                            vistas</span> --}}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>

                    <!-- Grid de Noticias Secundarias (2x2) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($news->slice(1, 4) as $newsItem)
                            <article class="bg-white rounded-lg shadow-md overflow-hidden group cursor-pointer">
                                <a href="/noticia/{{ $newsItem->slug }}">
                                    <!-- Imagen -->
                                    <div class="relative overflow-hidden h-48">
                                        <img src="{{ Storage::url($newsItem->featured_image) }}"
                                            alt="{{ $newsItem->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <!-- Categoría sobre la imagen -->
                                        <span
                                            class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded uppercase">
                                            {{ $newsItem->category->name }}
                                        </span>
                                    </div>
                                    <!-- Contenido -->
                                    <div class="p-4">
                                        <h3
                                            class="font-bold text-lg mb-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                                            {{ $newsItem->title }}
                                        </h3>
                                        <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                            {{ $newsItem->subtitle ?? Str::limit(strip_tags($newsItem->body), 100) }}
                                        </p>
                                        <div class="flex items-center gap-3 text-gray-500 text-xs">
                                            <span><i
                                                    class="far fa-clock mr-1"></i>{{ $newsItem->published_at->diffForHumans() }}</span>
                                            {{-- <span><i
                                                    class="far fa-eye mr-1"></i>{{ number_format($newsItem->views_count) }}
                                            </span> --}}
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                @endif
            </div>

            <!-- Columna Derecha - Sidebar -->
            <aside class="space-y-6">

                <!-- Últimas Noticias (Sidebar) -->

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4 pb-2 border-b-2 border-red-600">
                        <i class="fas fa-fire text-red-600 mr-2"></i>LAS MAS VISTAS
                    </h3>
                    <div class="space-y-4">
                        @forelse ($mostViewedNews as $index => $item)
                            <article class="group cursor-pointer">
                                <a href="/noticia/{{ $item->slug }}" class="flex gap-3">
                                    <span
                                        class="flex-shrink-0 text-3xl font-bold text-gray-300 group-hover:text-red-600 transition-colors">
                                        {{ $loop->index + 1 }}
                                    </span>
                                    <div>
                                        <span class="text-red-600 text-xs font-bold uppercase">
                                            {{ $item->category->name }}
                                        </span>
                                        <h4
                                            class="font-semibold text-sm line-clamp-3 group-hover:text-red-600 transition-colors">
                                            {{ $item->title }}
                                        </h4>

                                        <div
                                            class="mt-3 pt-3 border-t border-gray-50 flex justify-between items-center text-xs text-gray-500 font-medium">
                                            <span class="flex items-center gap-1">
                                                <i class="far fa-calendar-alt"></i>
                                                {{ $item->published_at->diffForHumans() }}
                                            </span>
                                            {{-- <span
                                                class="flex items-center gap-1 bg-blue-50 text-blue-700 px-2 py-1 rounded-full">
                                                <i class="fas fa-fire"></i>
                                                {{ number_format($item->views_count) }} visitas
                                            </span> --}}
                                        </div>
                                    </div>
                                </a>
                            </article>

                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-400 italic">No hay noticias destacadas en esta sección todavía.</p>
                            </div>
                        @endforelse
                    </div>
            </aside>
        </div>
        <!-- Más Noticias a todo el ancho de la pantalla -->
    </div>
    <hr class="my-10 border-gray-200">

    <section class="w-full">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-red-600 pl-3">
                Más Noticias
            </h2>
            {{-- <span class="text-sm text-gray-500 italic">2 más recientes por categoría</span> --}}
        </div>

        {{-- Usamos grid-cols-4 para que se vea amplio en pantallas grandes --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @forelse ($moreNews as $item)
                <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow group">
                    <a href="{{ route('news.show', $item->slug) }}">
                        <div class="relative h-44 overflow-hidden">
                            <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute top-0 right-0 p-2">
                                <span class="bg-red-600 text-white text-[10px] font-bold px-2 py-1 rounded uppercase">
                                    {{ $item->category->name }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-sm line-clamp-2 mb-2 group-hover:text-red-600 transition-colors">
                                {{ $item->title }}
                            </h3>
                            <div class="flex justify-between items-center text-[10px] text-gray-400 uppercase">
                                <span>{{ $item->published_at->format('d M, Y') }}</span>
                                {{-- <span><i class="far fa-eye"></i> {{ $item->views_count }}</span> --}}
                            </div>
                        </div>
                    </a>
                </article>
            @empty
                <div class="col-span-full py-10 text-center bg-gray-50 rounded-lg">
                    <p class="text-gray-500 italic">No hay noticias marcadas para esta sección.</p>
                </div>
            @endforelse
        </div>
    </section>
    <!-- Sección de Videos (si hay noticias con videos) -->
    @if ($news->filter(fn($n) => $n->videos->isNotEmpty())->isNotEmpty())
        <section class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold mb-6 pb-2 border-b-2 border-red-600">
                <i class="fas fa-play-circle text-red-600 mr-2"></i>Videos
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($news->filter(fn($n) => $n->videos->isNotEmpty())->take(3) as $videoNews)
                    <article class="group cursor-pointer">
                        <a href="/noticia/{{ $videoNews->slug }}">
                            <div class="relative overflow-hidden rounded-lg h-48 mb-3">
                                <img src="{{ Storage::url($videoNews->featured_image) }}" alt="{{ $videoNews->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                    <i
                                        class="fas fa-play-circle text-white text-5xl group-hover:scale-110 transition-transform"></i>
                                </div>
                            </div>
                            <h4 class="font-bold text-base line-clamp-2 group-hover:text-red-600 transition-colors">
                                {{ $videoNews->title }}
                            </h4>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    </div>
@endsection
