<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AdvertisementTypeEnum: string implements HasColor, HasIcon, HasLabel
{
    case IMAGE = 'imagen';
    case VIDEO = 'video';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::IMAGE => 'Imagen',
            self::VIDEO => 'Video',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::IMAGE => 'warning',
            self::VIDEO => 'orange',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::IMAGE => 'heroicon-m-photo',
            self::VIDEO => 'heroicon-m-video-camera',
        };
    }
}
