<?php

namespace App\Filament\Resources\ContactInfos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactInfosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logotipo')
                    ->disk('public'),
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('phone')
                    ->label('Teléfono'),
                TextColumn::make('email')
                    ->label('Correo Electrónico'),
                TextColumn::make('social_facebook')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('social_instagram')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('social_tiktok')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('social_twitter')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('social_youtube')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
