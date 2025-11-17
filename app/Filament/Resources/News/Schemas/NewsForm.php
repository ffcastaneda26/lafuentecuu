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
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                    Select::make('status')
                        ->label('Estado')
                        ->options([
                            'borrador' => 'Borrador',
                            'publicada' => 'Publicada',
                            'archivada' => 'Archivada',
                        ])
                        ->required()
                        ->default('borrador')
                        ->native(false)
                        ->live(debounce: 0), // Sin retraso
                    //    logger('No es un arreglo:');
                    //    logger('Devuelve:', [$status === NewStatusEnum::PUBLICADA->value]);
                    DateTimePicker::make('published_at')
                        ->label('Publicada el')
                        ->visible(function (Get $get) {
                            $status = $get('status');

                            return $status === NewStatusEnum::PUBLICADA->value;
                        })
                        ->required(function (Get $get) {
                            $status = $get('status');

                            return $status === NewStatusEnum::PUBLICADA->value;
                        })
                        ->validationMessages([
                            'required' => 'La fecha de publicación es obligatoria cuando la noticia es Publicada.',
                        ]),
                ]),
                Group::make()->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->label('Categoría')
                        ->required(),
                    FileUpload::make('featured_image')
                        ->label('Imagen Destacada')
                        ->image()
                        // ->imageEditor()
                        // ->imageEditorViewportWidth('1200')
                        // ->imageEditorViewportHeight('800')
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                        ->disk('public') // ✅ Disco público
                        ->directory('news') // ✅ Subdirectorio específico
                        ->visibility('public') // ✅ IMPORTANTE: Visibilidad pública
                        ->maxSize(5120) // 5MB
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $sanitizedName = Str::slug($originalName);
                            $extension = $file->getClientOriginalExtension();

                            return time().'_'.$sanitizedName.'.'.$extension;
                        })
                        ->helperText('Tamaño máximo: 5MB. Formatos: JPG, PNG, WebP'),

                ]),
                Group::make()->schema([
                    RichEditor::make('body')
                        ->label('Contenido')
                        ->required()
                        ->extraAttributes([
                            'style' => 'height: 200px; overflow-y: auto;',
                        ]),

                ])->columnSpanFull(),

            ]);
    }
}
