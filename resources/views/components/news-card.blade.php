{{-- resources/views/components/news-card.blade.php --}}

@props([
    'news',
    'variant' => 'default', // Variantes: default, horizontal, minimal, featured
    'showImage' => true,
    'showCategory' => true,
    'showExcerpt' => true,
    'showMeta' => true,
])

@php
    $variantClasses = [
        'default' => 'bg-white rounded-lg shadow-md overflow-hidden group cursor-pointer',
        'horizontal' => 'flex gap-4 bg-white rounded-lg shadow-md overflow-hidden group cursor-pointer',
        'minimal' => 'group cursor-pointer',
        'featured' => 'bg-white rounded-lg shadow-lg overflow-hidden group cursor-pointer',
    ];
@endphp

<article class="{{ $variantClasses[$variant] }} {{ $attributes->get('class') }}">
    <a href="/noticia/{{ $news->slug }}" class="{{ $variant === 'horizontal' ? 'flex gap-4 w-full' : 'block' }}">

        @if ($showImage)
            @if ($variant === 'horizontal')
                <!-- Imagen Horizontal -->
                <div class="flex-shrink-0 w-32 h-24 overflow-hidden rounded">
                    <p>Para monitoriear línea 28</p>
                    <img src="{{ Storage::url($news->featured_image) }}" alt="Línea 29"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
            @elseif($variant === 'featured')
                <!-- Imagen Featured (Grande) -->
                <div class="relative overflow-hidden h-96">
                    <p>Para monitoriear línea 35</p>

                    <img src="{{ asset('storage/' . $news->featured_image) }}" alt="Línea 37"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">


                    @if ($showCategory)
                        <span
                            class="absolute top-4 left-4 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded uppercase">
                            {{ $news->category->name }}
                        </span>
                    @endif

                    <div
                        class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-black/70 to-transparent p-6">
                        <h2 class="text-white text-3xl font-bold mb-2 line-clamp-2">
                            {{ $news->title }}
                        </h2>

                        @if ($showExcerpt && $news->subtitle)
                            <p class="text-gray-200 text-sm line-clamp-2 mb-3">
                                {{ $news->subtitle }}
                            </p>
                        @endif

                        @if ($showMeta)
                            <div class="flex items-center gap-4 text-gray-300 text-xs">
                                <span><i class="far fa-clock mr-1"></i>{{ $news->published_at->diffForHumans() }}</span>

                            </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Imagen Default -->
                <div class="relative overflow-hidden h-48">

                    <img src="{{ Storage::url($news->featured_image) }}" alt="{{ $news->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                    @if ($showCategory)
                        <span
                            class="absolute top-2 left-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded uppercase">
                            {{ $news->category->name }}
                        </span>
                    @endif
                </div>
            @endif
        @endif
        @if ($variant !== 'featured')
            <!-- Contenido de la tarjeta -->
            <div class="{{ $variant === 'horizontal' ? 'flex-1' : 'p-4' }}">

                @if ($showCategory && !$showImage)
                    <span class="text-red-600 text-xs font-bold uppercase block mb-1">
                        {{ $news->category->name }}
                    </span>
                @endif

                <h3
                    class="font-bold {{ $variant === 'horizontal' ? 'text-base' : 'text-lg' }} mb-2 line-clamp-2 group-hover:text-red-600 transition-colors">
                    {{ $news->title }}
                </h3>

                @if ($showExcerpt && $variant === 'default')
                    <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                        {{ $news->subtitle ?? Str::limit(strip_tags($news->body), 100) }}
                    </p>
                @endif

                @if ($showMeta)
                    <div
                        class="flex items-center gap-3 text-gray-500 text-xs {{ $variant === 'horizontal' ? 'mt-2' : '' }}">
                        <span><i class="far fa-clock mr-1"></i>{{ $news->published_at->diffForHumans() }}</span>

                    </div>
                @endif
            </div>
        @endif
    </a>
</article>
