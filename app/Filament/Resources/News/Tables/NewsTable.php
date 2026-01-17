<?php

namespace App\Filament\Resources\News\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->afterStateUpdated(function ($record, $state) {
                        // Opcional: Puedes disparar una notificación si lo deseas
                        // Notification::make()->title('Estado actualizado')->success()->send();
                    }),
                ToggleColumn::make('is_most_viewed')
                    ->label('¿Más Vistas?')
                    ->onIcon('heroicon-m-check')
                    ->offIcon('heroicon-m-x-mark')
                    ->afterStateUpdated(function ($record, $state) {
                        // Opcional: Notificación rápida
                        // Notification::make()->title('Sección Más Vistas actualizada')->info()->send();
                    }),
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
                //
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
