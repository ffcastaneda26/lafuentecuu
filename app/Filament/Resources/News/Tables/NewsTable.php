<?php

namespace App\Filament\Resources\News\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class NewsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('featured_image')
                    ->label('Imagen Principal')
                    ->disk('public'),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->limit(30)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn($record) => $record->status_color),
                TextColumn::make('views_count')
                    ->label('Vistas')
                    ->sortable()
                    ->searchable(),

                // IconColumn::make('featured')
                //     ->label('¿Destacada?')
                //     ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('published_at')
                    ->label('Publicada')
                    ->since()
                    ->sortable(),
                ToggleColumn::make('is_more_news')
                    ->label('¿Más Noticias?')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark')
                    ->afterStateUpdated(function ($record, $state) {}),
                ToggleColumn::make('is_most_viewed')
                    ->label('¿Más Vistas?')
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark')
                    ->afterStateUpdated(function ($record, $state) {}),
                TextColumn::make('views_count')
                    ->label('Vistas por')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),


                TextColumn::make('user.name')
                    ->label('Creada Por')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('solo_con_orden')
                    ->label('Solo con Orden')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('sort_order', '>', 0)),
                Filter::make('more_news')
                    ->label('¿En Más Noticias?')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('is_more_news', true)),
                Filter::make('more_viewed')
                    ->label('¿En Más Vistas?')
                    ->toggle()
                    ->query(fn(Builder $query) => $query->where('is_most_viewed', true))
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
