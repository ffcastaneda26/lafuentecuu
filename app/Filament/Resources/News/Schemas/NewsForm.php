<?php

namespace App\Filament\Resources\News\Schemas;

use App\Enums\NewStatusEnum;
use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    TextInput::make('title')
                        ->label('Tìtulo')
                        ->required()
                        ->maxLength(150)
                        ->rules([
                            fn ($record) => function (string $attribute, $value, $fail) use ($record) {
                                $exists = News::whereRaw('LOWER(title) = ?', [strtolower($value)])
                                    ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                                    ->exists();

                                if ($exists) {
                                    $fail('Ya existe una noticia con este tìtulo');
                                }
                            },
                        ])
                        ->validationMessages([
                            'required' => 'El tìtulo de la noticia es obligatorio.',
                            'max' => 'El Tìtulo no puede superar :max caracteres.',
                        ])
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('slug', Str::slug($state));
                        })
                        ->live(onBlur: true)
                        ->columnSpanFull(),
                    TextInput::make('subtitle')
                        ->required()
                        ->maxLength(150)
                        ->label('Subtìtulo')
                        ->validationMessages([
                            'required' => 'El Subtítulo de la noticia es obligatorio.',
                            'max' => 'El Subtítulo no puede superar :max caracteres.',
                        ]),
                    FileUpload::make('featured_image')
                        ->image(),
                ]),
                Group::make()->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->label('Categoría')
                        ->required(),
                    DateTimePicker::make('published_at')
                        ->label('Publicada el'),
                    Select::make('status')
                        ->label('Estado')
                        // ->options([
                        //     NewStatusEnum::BORRADOR->value => NewStatusEnum::BORRADOR->getLabel(),
                        //     NewStatusEnum::PUBLICADA->value => NewStatusEnum::PUBLICADA->getLabel(),
                        //     NewStatusEnum::ARCHIVADA->value => NewStatusEnum::ARCHIVADA->getLabel(),
                        // ])
                        ->options(NewStatusEnum::class)

                        ->required()
                        ->default(NewStatusEnum::BORRADOR->value)
                        ->native(false),

                ]),

                RichEditor::make('body')
                    ->label('Contenido')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
