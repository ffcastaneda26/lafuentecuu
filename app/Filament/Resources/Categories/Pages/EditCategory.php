<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar Categoría')
                ->requiresConfirmation()
                ->modalHeading('Eliminar Categoría')
                ->modalDescription('¿Estás seguro? Las noticias asociadas quedarán sin categoría.')
                ->successNotificationTitle('Categoría eliminada'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // ✅ ACTUALIZAR SLUG SI CAMBIA EL NOMBRE
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Solo regenerar el slug si cambió el nombre
        if (isset($data['name']) && $data['name'] !== $this->record->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $this->record->id);
        }

        return $data;
    }

    // ✅ MÉTODO PARA GENERAR SLUG ÚNICO (IGNORANDO EL REGISTRO ACTUAL)
    private function generateUniqueSlug(string $name, int $ignoreId): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Verificar si el slug ya existe (ignorando el registro actual)
        while (\App\Models\Category::where('slug', $slug)
            ->where('id', '!=', $ignoreId)
            ->exists()) {
            $slug = $originalSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    // ✅ NOTIFICACIÓN PERSONALIZADA
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Categoría actualizada exitosamente';
    }

    protected function afterSave(): void
    {
        Notification::make()
            ->success()
            ->title('¡Categoría Actualizada!')
            ->body("La categoría '{$this->record->name}' con slug '{$this->record->slug}' ha sido actualizada correctamente.")
            ->send();
    }
}
