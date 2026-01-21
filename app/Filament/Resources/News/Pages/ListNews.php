<?php

namespace App\Filament\Resources\News\Pages;

use App\Filament\Resources\News\NewsResource;
use App\Models\Category;
use App\Models\News;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $tabs = [];

        // 1. Pestaña para "Todas"
        $tabs['all'] = Tab::make('Todas')
            ->badge(fn() => News::count());

        // 2. Pestaña "Orden" CORREGIDA
        $tabs['main'] = Tab::make('Orden')
            ->modifyQueryUsing(fn(Builder $query) => $query->where('sort_order', '>', 0)) // <-- Esto faltaba
            ->badge(fn() => News::where('sort_order', '>', 0)->count());

        // 3. Generar pestañas dinámicas por categoría
        $categories = Category::active()->ordered()->get();

        foreach ($categories as $category) {
            $tabs[$category->slug] = Tab::make($category->name)
                ->modifyQueryUsing(fn(Builder $query) => $query->where('category_id', $category->id))
                ->badge(
                    fn() => News::where('category_id', $category->id)
                        ->published()
                        ->where('sort_order', 0)
                        ->count()
                );
        }

        return $tabs;
    }
}
