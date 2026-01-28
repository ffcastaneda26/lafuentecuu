<div>
    @if ($ads->isNotEmpty())
        <div class="relative group bg-gray-50 rounded-xl shadow-inner p-2 mb-6" x-data="adSlider()">
            <div id="ad-slider-container"
                class="flex overflow-x-auto snap-x snap-mandatory scroll-smooth no-scrollbar gap-4 h-44 items-center px-4">
                @foreach ($ads as $ad)
                    <div class="ad-item flex-shrink-0 w-full md:w-1/2 lg:w-1/3 snap-center h-full">
                        <div
                            class="relative h-full w-full bg-white rounded-lg overflow-hidden border border-gray-100 shadow-sm">

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

            <button @click="scroll('left')"
                class="absolute left-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-md ml-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            <button @click="scroll('right')"
                class="absolute right-0 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white p-2 rounded-full shadow-md mr-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <script>
            function adSlider() {
                return {
                    slider: null,
                    timer: null,
                    init() {
                        this.slider = document.getElementById('ad-slider-container');
                        this.startAutoScroll();
                    },
                    scroll(direction) {
                        this.resetAutoScroll();
                        const amount = this.slider.offsetWidth / (window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ?
                            2 : 1));
                        const max = this.slider.scrollWidth - this.slider.clientWidth;

                        if (direction === 'right') {
                            (this.slider.scrollLeft >= max - 10) ? this.slider.scrollTo({
                                left: 0,
                                behavior: 'smooth'
                            }): this.slider.scrollBy({
                                left: amount,
                                behavior: 'smooth'
                            });
                        } else {
                            (this.slider.scrollLeft <= 10) ? this.slider.scrollTo({
                                left: max,
                                behavior: 'smooth'
                            }): this.slider.scrollBy({
                                left: -amount,
                                behavior: 'smooth'
                            });
                        }
                    },
                    startAutoScroll() {
                        this.timer = setInterval(() => {
                            this.scroll('right');
                        }, 4000);
                    },
                    resetAutoScroll() {
                        clearInterval(this.timer);
                        this.startAutoScroll();
                    }
                }
            }
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
</div>
