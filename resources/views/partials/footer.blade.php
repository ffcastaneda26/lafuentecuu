    <footer class="bg-gray-900 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <!-- Navegación de categorías en footer -->
            @include('partials.categories_nav')

            <!-- Información del sitio -->
            <div class="grid md:grid-cols-3 gap-8 mb-6 pt-6 border-t border-gray-700">
                <!-- Sobre nosotros -->
                @include('partials.about')
                <!-- Contacto -->
                <div>
                    <h4 class="text-lg font-bold mb-3">Contacto</h4>
                    <ul class="space-y-2 text-sm">
                        @if (!empty($contactInfo->email))
                            <li class="flex items-center gap-2">
                                <i class="fas fa-envelope text-gray-400"></i>
                                <a href="mailto:{{ $contactInfo->email }}" class="text-gray-400 hover:text-white">
                                    {{ $contactInfo->email }}
                                </a>
                            </li>
                        @endif
                        @if (!empty($contactInfo->phone))
                            <li class="flex items-center gap-2">
                                <i class="fas fa-phone text-gray-400"></i>
                                <span class="text-gray-400">{{ $contactInfo->phone }}</span>
                            </li>
                        @endif
                        @if (!empty($contactInfo->address))
                            <li class="flex items-center gap-2">
                                @include('partials.ubication')
                            </li>
                        @endif
                    </ul>
                </div>

                <!-- Redes Sociales -->
                <div>
                    <h4 class="text-lg font-bold mb-3">Síguenos</h4>
                    <div class="flex gap-4">
                        @if (!empty($contactInfo->social_facebook))
                            <a href="{{ $contactInfo->social_facebook }}" target="_blank"
                                class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif

                        @if (!empty($contactInfo->social_tiktok))
                            <a href="{{ $contactInfo->social_tiktok }}" target="_blank"
                                class="w-10 h-10 bg-black hover:bg-gray-800 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-tiktok"></i>
                            </a>
                        @endif

                        @if (!empty($contactInfo->social_instagram))
                            <a href="{{ $contactInfo->social_instagram }}" target="_blank"
                                class="w-10 h-10 bg-black hover:bg-gray-800 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif



                        @if (!empty($contactInfo->social_twitter))
                            <a href="{{ $contactInfo->social_twitter }}" target="_blank"
                                class="w-10 h-10 bg-blue-400 hover:bg-blue-500 rounded-full flex items-center justify-center transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-700 pt-6 text-center">
                <p class="text-gray-400 text-sm">
                    &copy; {{ date('Y') }} Portal de Noticias. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </footer>
