<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

//  $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->comment('Borrador-Publicada-Archivada');
enum NewStatusEnum: string implements HasColor, HasIcon, HasLabel
{
    case BORRADOR = 'borrador';
    case PUBLICADA = 'publicada';
    case ARCHIVADA = 'archivada';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BORRADOR => 'Borrador',
            self::PUBLICADA => 'Publicada',
            self::ARCHIVADA => 'Archivada',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::BORRADOR => 'warning',
            self::PUBLICADA => 'orange',
            self::ARCHIVADA => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BORRADOR => 'heroicon-m-bell-alert',
            self::PUBLICADA => 'heroicon-m-book-open',
            self::ARCHIVADA => 'heroicon-m-numbered-list',
        };
    }
}
