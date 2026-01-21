<?php

namespace App\Filament\Widgets;

use App\Models\News;
use App\Models\Sponsor;
use App\Models\Advertisement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // Opcional: Controla cada cuánto se actualizan los datos automáticamente
    // protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            Stat::make('Noticias Publicadas', News::published()->count())
                ->description('Total visible en el portal')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),

            Stat::make('Campañas Activas', Advertisement::active()->count())
                ->description('Anunicos en curso')
                ->descriptionIcon('heroicon-m-hand-raised')
                ->color('primary'),

            Stat::make('Alcance Total', number_format(News::sum('views_count')))
                ->description('Visualizaciones acumuladas')
                ->descriptionIcon('heroicon-m-eye')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('info'),
        ];
    }
}
