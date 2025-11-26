        <nav class="bg-gray-900">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between py-3">
                    <!-- Botón Home -->
                    <a href="/"
                        class="flex-shrink-0 px-2 md:px-4 text-white hover:bg-gray-800 transition-colors rounded">
                        <i class="fas fa-home text-lg"></i>
                    </a>

                    <!-- Items de Categorías - Desktop -->
                    <div class="hidden md:flex items-center space-x-1 flex-1 justify-center overflow-x-auto">
                        @foreach ($categories as $category)
                            <a href="/{{ $category->slug }}"
                                class="px-4 py-3 text-white hover:bg-gray-800 transition-colors whitespace-nowrap text-sm font-medium uppercase rounded">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center gap-2">
                        <!-- Búsqueda -->
                        <button class="px-2 md:px-4 text-white hover:text-gray-300 transition-colors">
                            <i class="fas fa-search text-lg"></i>
                        </button>

                        <!-- Botón Menú Mobile -->
                        <button id="mobile-menu-button"
                            class="md:hidden px-2 text-white hover:text-gray-300 transition-colors">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                </div>

                <!-- Menú Mobile -->
                <div id="mobile-menu" class="hidden md:hidden pb-4">
                    <div class="flex flex-col space-y-1">
                        @foreach ($categories as $category)
                            <a href="/{{ $category->slug }}"
                                class="px-4 py-3 text-white hover:bg-gray-800 transition-colors text-sm font-medium uppercase rounded">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </nav>
