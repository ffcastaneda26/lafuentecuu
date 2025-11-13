<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateNews extends CreateRecord
{
    protected static string $resource = NewsResource::class;

    // ✅ ASIGNAR USER_ID ANTES DE CREAR
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']);
        // if($data['published_at']){
        //     $data['status'] = 'PUBLICADA';
        // }

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Noticia creada exitosamente';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
