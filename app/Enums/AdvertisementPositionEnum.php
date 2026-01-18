<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AdvertisementPositionEnum: string implements HasColor, HasIcon, HasLabel
{
    case HEADER = 'encabezado';
    case LEFT_SIDE = 'izquierda';
    case RIGHT_SIDE = 'derecha';
    case FOOTER = 'pie';
    case INLINE = 'linea';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::HEADER => 'Encabezado',
            self::LEFT_SIDE => 'Izquierda',
            self::RIGHT_SIDE => 'Derecha',
            self::FOOTER => 'Pie Página',
            self::INLINE => 'En Línea',
        };
    }

    public function getColor(): array|string|null
    {
        return match ($this) {
            self::HEADER => 'warning',
            self::LEFT_SIDE => 'orange',
            self::RIGHT_SIDE => 'primary',
            self::FOOTER => 'secondary',
            self::INLINE => 'green',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::HEADER => 'heroicon-m-arrow-uturn-up',
            self::LEFT_SIDE => 'heroicon-m-arrow-left',
            self::RIGHT_SIDE => 'heroicon-m-arrow-right',
            self::FOOTER => 'heroicon-m-arrow-long-down',
            self::INLINE => 'heroicon-m-arrows-right-left',
        };
    }
}
