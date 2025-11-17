<?php

namespace App\Filament\Resources\News\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('caption')
                    ->label('Descripción'),
                FileUpload::make('image_path')
                    ->label('Imagen Destacada')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                    ->disk('public')
                    ->directory('news')
                    ->maxSize(5120) // 5MB
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $sanitizedName = Str::slug($originalName);
                        $extension = $file->getClientOriginalExtension();

                        return time().'_'.$sanitizedName.'.'.$extension;
                    })->columnSpanFull(),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Imágenes')
            ->recordTitleAttribute('title')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Imagen')
                    ->disk('public'),
                TextColumn::make('caption')
                    ->label('Descripción'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Agregar Imagen a la Noticia'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
