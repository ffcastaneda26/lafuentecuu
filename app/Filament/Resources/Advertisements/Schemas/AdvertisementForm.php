<?php

namespace App\Filament\Resources\Advertisements\Schemas;

use App\Enums\AdvertisementPositionEnum;
use App\Enums\AdvertisementTypeEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Forms\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AdvertisementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Select::make('sponsor_id')
                        ->label('Patrocinador')
                        ->relationship(
                            name: 'sponsor',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn(Builder $query) => $query->where('active', true)
                        )
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->columnSpanFull(),
                    Select::make('type')
                        ->label('Tipo')
                        ->options(AdvertisementTypeEnum::class)
                        ->default('imagen')
                        ->required(),
                    TextInput::make('priority')
                        ->required()
                        ->numeric()
                        ->default(0),
                    Select::make('position')
                        ->label('Posición')
                        ->options(AdvertisementPositionEnum::class)
                        ->default('encabezado')
                        ->required(),

                    DatePicker::make('start_date')
                        ->label('Inicio')
                        ->date(),
                    DatePicker::make('end_date')
                        ->label('Final')
                        ->date(),
                    Toggle::make('active')
                        ->label('¿Activo?')
                        ->default(true),
                ])->columns(3),

                Group::make()->schema([

                    TextInput::make('click_url')
                        ->label('Liga al hacer click sobre anuncio')
                        ->url(),
                    FileUpload::make('media_url')
                        ->label('Anuncio en sí')
                        ->acceptedFileTypes([
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/jpg',
                            'image/gif', // ✅ GIF agregado
                            'video/mp4', // ✅ Videos agregados
                            'video/quicktime', // MOV
                            'video/x-msvideo', // AVI
                            'video/webm', // WebM
                            'video/x-matroska', // MKV
                        ])
                        ->disk('public')
                        ->directory('advertisements')
                        ->visibility('public')
                        ->maxSize(51200)
                        ->required()
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $sanitizedName = Str::slug($originalName);
                            $extension = $file->getClientOriginalExtension();

                            return time() . '_' . $sanitizedName . '.' . $extension;
                        })
                        ->columnSpanFull(),
                ]),






            ]);
    }
}
