<div class="flex justify-center items-center py-2">
    @php
        $state = $getState();
        $record = $getRecord();
        $url = \Illuminate\Support\Facades\Storage::url($state);

        // Definimos dimensiones estándar (puedes ajustarlas aquí)
        $width = 'w-2'; // Aprox 96px
        $height = 'h-3'; // Aprox 56px
    @endphp

    <div
        class=" {{ $width }} {{ $height }} overflow-hidden rounded-lg border border-gray-200 shadow-sm bg-gray-50 flex items-center justify-center">
        @if ($record->type === \App\Enums\AdvertisementTypeEnum::VIDEO->value)
            {{-- Video con tamaño fijo --}}
            <video class="w-3 h-3 object-cover" muted onmouseover="this.play()"
                onmouseout="this.pause(); this.currentTime = 0;">
                <source src="{{ $url }}" type="video/mp4">
            </video>
        @else
            {{-- Imagen con tamaño fijo --}}
            <img src="{{ $url }}" class="w-3 h-3 object-cover" alt="Anuncio">
        @endif
    </div>
</div>
