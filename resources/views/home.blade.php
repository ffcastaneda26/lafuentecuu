@extends('layouts.main')

@section('title', 'Inicio - Portal de Noticias')

@section('content')
    <div class="space-y-6">
        <!-- Banner de Patrocinadores Principal (Horizontal) -->
        @if ($sponsors->isNotEmpty())
            <div class="bg-white rounded-lg shadow-sm p-4 overflow-hidden h-48">
                <div class="flex items-center justify-center gap-6 overflow-x-auto">
                    @foreach ($sponsors as $sponsor)
                        <a href="{{ $sponsor->website }}" target="_blank" class="flex-shrink-0">
                            <img src="{{ Storage::url($sponsor->logo) }}" alt="{{ $sponsor->name }}"
                                class="h-44 p-2 object-contain hover:opacity-80 transition-opacity">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Grid Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Columna Izquierda - Noticias Principales (2 columnas en lg) -->
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
                                        <span><i
                                                class="far fa-eye mr-1"></i>{{ number_format($featuredNews->views_count) }}
                                            vistas</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                    {{ $news->count() }}
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
                                            <span><i
                                                    class="far fa-eye mr-1"></i>{{ number_format($newsItem->views_count) }}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <!-- Lista de Noticias Adicionales -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold mb-4 pb-2 border-b-2 border-red-600">Más Noticias</h2>
                        <div class="space-y-4">
                            @foreach ($news->slice(5, 8) as $newsItem)
                                <article
                                    class="flex gap-4 pb-4 border-b border-gray-200 last:border-b-0 group cursor-pointer">
                                    <a href="/noticia/{{ $newsItem->slug }}" class="flex gap-4 w-full">
                                        <!-- Thumbnail -->
                                        <div class="flex-shrink-0 w-32 h-24 overflow-hidden rounded">
                                            <img src="{{ Storage::url($newsItem->featured_image) }}"
                                                alt="{{ $newsItem->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        </div>
                                        <!-- Contenido -->
                                        <div class="flex-1">
                                            <span class="text-red-600 text-xs font-bold uppercase">
                                                {{ $newsItem->category->name }}
                                            </span>
                                            <h4
                                                class="font-bold text-base mb-1 line-clamp-2 group-hover:text-red-600 transition-colors">
                                                {{ $newsItem->title }}
                                            </h4>
                                            <div class="flex items-center gap-3 text-gray-500 text-xs mt-2">
                                                <span><i
                                                        class="far fa-clock mr-1"></i>{{ $newsItem->published_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Columna Derecha - Sidebar -->
            <aside class="space-y-6">

                <!-- Banner Vertical de Patrocinador -->
                @if ($sponsors->where('status', 'active')->count() > 3)
                    <div class="bg-white rounded-lg shadow-md p-4 sticky top-4">
                        <p class="text-xs text-gray-500 text-center mb-2">PUBLICIDAD</p>
                        <a href="{{ $sponsors->where('status', 'active')->skip(3)->first()->website }}" target="_blank">
                            <img src="{{ $sponsors->where('status', 'active')->skip(3)->first()->logo }}"
                                alt="Patrocinador" class="w-full h-auto object-contain hover:opacity-80 transition-opacity">
                        </a>
                    </div>
                @endif

                <!-- Últimas Noticias (Sidebar) -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4 pb-2 border-b-2 border-red-600">
                        <i class="fas fa-fire text-red-600 mr-2"></i>LAS MAS VISTAS
                    </h3>
                    <div class="space-y-4">
                        @foreach ($news->sortByDesc('views_count')->take(5) as $trendingNews)
                            <article class="group cursor-pointer">
                                <a href="/noticia/{{ $trendingNews->slug }}" class="flex gap-3">
                                    <span
                                        class="flex-shrink-0 text-3xl font-bold text-gray-300 group-hover:text-red-600 transition-colors">
                                        {{ $loop->index + 1 }}
                                    </span>
                                    <div>
                                        <span class="text-red-600 text-xs font-bold uppercase">
                                            {{ $trendingNews->category->name }}
                                        </span>
                                        <h4
                                            class="font-semibold text-sm line-clamp-3 group-hover:text-red-600 transition-colors">
                                            {{ $trendingNews->title }}
                                        </h4>
                                        <span class="text-gray-500 text-xs mt-1 inline-block">
                                            <i class="far fa-eye mr-1"></i>{{ number_format($trendingNews->views_count) }}
                                        </span>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>

                <!-- Categorías Populares -->
                {{-- <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4 pb-2 border-b-2 border-red-600">Secciones</h3>
                    <div class="space-y-2">
                        @foreach ($categories as $category)
                            <a href="/{{ $category->slug }}"
                                class="flex items-center justify-between py-2 px-3 rounded hover:bg-gray-100 transition-colors group">
                                <span class="font-medium text-gray-700 group-hover:text-red-600">
                                    {{ $category->name }}
                                </span>
                                <i class="fas fa-chevron-right text-gray-400 group-hover:text-red-600"></i>
                            </a>
                        @endforeach
                    </div>
                </div> --}}

            </aside>
        </div>

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
                                    <img src="{{ Storage::url($videoNews->featured_image) }}"
                                        alt="{{ $videoNews->title }}"
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
