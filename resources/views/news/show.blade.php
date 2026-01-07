<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $news->title }} - Portal de Noticias</title>
    <meta name="description" content="{{ $news->subtitle ?? Str::limit(strip_tags($news->body), 160) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .news-content {
            line-height: 1.8;
        }

        .news-content p {
            margin-bottom: 1rem;
        }

        .news-content h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 1.5rem 0 1rem;
        }

        .news-content h3 {
            font-size: 1.25rem;
            font-weight: bold;
            margin: 1.25rem 0 0.75rem;
        }

        .news-content ul,
        .news-content ol {
            margin-left: 2rem;
            margin-bottom: 1rem;
        }

        .news-content li {
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header (igual que welcome) -->

    @include('partials.header')

    <!-- Contenido -->
    <main class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenido Principal -->
            <article class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                <!-- Breadcrumb -->
                <nav class="text-sm text-gray-500 mb-4">
                    <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('category.show', $news->category->slug) }}" class="hover:text-blue-600">
                        {{ $news->category->name }}
                    </a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-700">{{ Str::limit($news->title, 40) }}</span>
                </nav>

                <!-- Categoría -->
                <span class="inline-block px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full mb-4">
                    {{ $news->category->name }}
                </span>

                <!-- Título -->
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    {{ $news->title }}
                </h1>

                <!-- Subtítulo -->
                @if ($news->subtitle)
                    <h2 class="text-xl text-gray-600 mb-6">
                        {{ $news->subtitle }}
                    </h2>
                @endif

                <!-- Metadatos -->
                <div class="flex items-center text-sm text-gray-500 mb-6 pb-6 border-b">
                    <div class="flex items-center mr-6">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $news->user->name }}</span>
                    </div>
                    <div class="flex items-center mr-6">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $news->published_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd"
                                d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ $news->views_count }} vistas</span>
                    </div>
                </div>

                <!-- Imagen Principal -->
                @if ($news->featured_image)
                    <figure class="mb-6">
                        <img src="{{ Storage::url($news->featured_image) }}" alt="{{ $news->title }}"
                            class="w-full h-auto rounded-lg shadow-lg">
                    </figure>
                @endif

                <!-- Compartir en Redes Sociales -->
                <div class="flex items-center space-x-3 mb-6 pb-6 border-b">
                    <span class="text-sm font-semibold text-gray-600">Compartir:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                        target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                        Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($news->title) }}"
                        target="_blank" class="bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-500 text-sm">
                        Twitter
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($news->title . ' ' . url()->current()) }}"
                        target="_blank" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                        WhatsApp
                    </a>
                </div>

                <!-- Cuerpo de la Noticia -->
                <div class="news-content text-gray-800 text-lg">
                    {!! $news->body !!}
                </div>

                <!-- Anuncio Inline 1 -->
                @if (isset($advertisements['inline-1']) && $advertisements['inline-1'])
                    <div class="my-8 p-4 bg-gray-50 rounded-lg text-center">
                        @include('partials.advertisement', ['ad' => $advertisements['inline-1']])
                    </div>
                @endif

                <!-- Imágenes Secundarias -->
                @if ($news->images->count() > 0)
                    <div class="my-8">
                        <h3 class="text-2xl font-bold mb-4">Galería de Imágenes</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($news->images as $image)
                                <figure class="group">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption }}"
                                        class="w-full h-48 object-cover rounded-lg shadow-md group-hover:shadow-xl transition-shadow cursor-pointer">
                                    @if ($image->caption)
                                        <figcaption class="text-sm text-gray-600 mt-2">{{ $image->caption }}
                                        </figcaption>
                                    @endif
                                </figure>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Videos -->
                @if ($news->videos->count() > 0)
                    <div class="my-8">
                        <h3 class="text-2xl font-bold mb-4">Videos Relacionados</h3>
                        <div class="space-y-4">
                            @foreach ($news->videos as $video)
                                <div class="video-container">
                                    @if ($video->title)
                                        <h4 class="text-lg font-semibold mb-2">{{ $video->title }}</h4>
                                    @endif
                                    @if (str_contains($video->video_url, 'youtube.com') || str_contains($video->video_url, 'youtu.be'))
                                        @php
                                            parse_str(parse_url($video->video_url, PHP_URL_QUERY), $vars);
                                            $videoId = $vars['v'] ?? '';
                                            if (empty($videoId) && str_contains($video->video_url, 'youtu.be')) {
                                                $videoId = basename(parse_url($video->video_url, PHP_URL_PATH));
                                            }
                                        @endphp
                                        <iframe width="100%" height="400"
                                            src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen class="rounded-lg"></iframe>
                                    @elseif(str_contains($video->video_url, 'vimeo.com'))
                                        @php
                                            $videoId = basename(parse_url($video->video_url, PHP_URL_PATH));
                                        @endphp
                                        <iframe src="https://player.vimeo.com/video/{{ $videoId }}" width="100%"
                                            height="400" frameborder="0"
                                            allow="autoplay; fullscreen; picture-in-picture" allowfullscreen
                                            class="rounded-lg"></iframe>
                                    @else
                                        <video controls class="w-full rounded-lg">
                                            <source src="{{ $video->video_url }}" type="video/mp4">
                                            Tu navegador no soporta la etiqueta de video.
                                        </video>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Anuncio Inline 2 -->
                @if (isset($advertisements['inline-2']) && $advertisements['inline-2'])
                    <div class="my-8 p-4 bg-gray-50 rounded-lg text-center">
                        @include('partials.advertisement', ['ad' => $advertisements['inline-2']])
                    </div>
                @endif

                <!-- Enlaces a Redes Sociales de la Noticia -->
                @if ($news->socialLinks->count() > 0)
                    <div class="my-8 p-6 bg-blue-50 rounded-lg">
                        <h3 class="text-xl font-bold mb-4">Síguenos en Redes Sociales</h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($news->socialLinks as $link)
                                <a href="{{ $link->url }}" target="_blank"
                                    class="flex items-center bg-white px-4 py-2 rounded-lg shadow hover:shadow-md transition-shadow">
                                    @switch($link->platform)
                                        @case('facebook')
                                            <span class="text-blue-600 mr-2">📘</span>
                                        @break

                                        @case('instagram')
                                            <span class="text-pink-600 mr-2">📷</span>
                                        @break

                                        @case('tiktok')
                                            <span class="text-gray-800 mr-2">🎵</span>
                                        @break

                                        @case('twitter')
                                            <span class="text-blue-400 mr-2">🐦</span>
                                        @break

                                        @default
                                            <span class="mr-2">🔗</span>
                                    @endswitch
                                    <span class="capitalize">{{ $link->platform }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tags/Etiquetas -->
                <div class="mt-8 pt-6 border-t">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-sm font-semibold text-gray-600">Etiquetas:</span>
                        <a href="{{ route('category.show', $news->category->slug) }}"
                            class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm hover:bg-gray-300">
                            #{{ $news->category->name }}
                        </a>
                    </div>
                </div>

                <!-- Noticias Relacionadas -->
                @if ($relatedNews->count() > 0)
                    <div class="mt-12 pt-8 border-t">
                        <h3 class="text-2xl font-bold mb-6">Noticias Relacionadas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($relatedNews as $related)
                                <article
                                    class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    <a href="{{ route('news.show', $related->slug) }}">
                                        @if ($related->featured_image)
                                            <img src="{{ Storage::url($related->featured_image) }}"
                                                alt="{{ $related->title }}" class="w-full h-32 object-cover">
                                        @endif
                                        <div class="p-4">
                                            <h4 class="font-bold line-clamp-2 mb-2">{{ $related->title }}</h4>
                                            <span
                                                class="text-xs text-gray-500">{{ $related->published_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </article>

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <!-- Anuncios Sidebar -->
                @if (isset($advertisements['sidebar']) && $advertisements['sidebar']->count() > 0)
                    <div class="mb-6">
                        @foreach ($advertisements['sidebar'] as $ad)
                            <div class="mb-4 bg-white rounded-lg shadow-md p-4">
                                @include('partials.advertisement', ['ad' => $ad])
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Últimas Noticias -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <h3 class="text-lg font-bold mb-4">Últimas Noticias</h3>
                    @php
                        $latestNews = \App\Models\News::published()
                            ->where('id', '!=', $news->id)
                            ->orderBy('published_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    <ul class="space-y-3">
                        @foreach ($latestNews as $latest)
                            <li class="border-b pb-3 last:border-b-0">
                                <a href="{{ route('news.show', $latest->slug) }}" class="hover:text-blue-600">
                                    <div class="font-semibold text-sm line-clamp-2">{{ $latest->title }}</div>
                                    <span
                                        class="text-xs text-gray-500">{{ $latest->published_at->diffForHumans() }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Más Leídas -->
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold mb-4">Más Leídas</h3>
                    @php
                        $mostViewed = \App\Models\News::published()
                            ->where('id', '!=', $news->id)
                            ->orderBy('views_count', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    <ul class="space-y-3">
                        @foreach ($mostViewed as $popular)
                            <li class="border-b pb-3 last:border-b-0">
                                <a href="{{ route('news.show', $popular->slug) }}" class="hover:text-blue-600">
                                    <div class="font-semibold text-sm line-clamp-2">{{ $popular->title }}</div>
                                    <span class="text-xs text-gray-500">{{ $popular->views_count }} vistas</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Portal de Noticias</h3>
                    @if ($contactInfo && $contactInfo->about_text)
                        <p class="text-sm text-gray-300">
                            {!! Str::limit(strip_tags($contactInfo->about_text), 150) !!}
                        </p>
                    @endif
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Categorías</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ route('category.show', $category->slug) }}"
                                    class="text-gray-300 hover:text-white">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contacto</h3>
                    @if ($contactInfo)
                        <ul class="space-y-2 text-sm text-gray-300">
                            @if ($contactInfo->phone)
                                <li>📞 {{ $contactInfo->phone }}</li>
                            @endif
                            @if ($contactInfo->email)
                                <li>✉️ {{ $contactInfo->email }}</li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Portal de Noticias. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>

</html>
