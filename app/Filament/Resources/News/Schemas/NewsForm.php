<?php

namespace App\Filament\Resources\News\Schemas;

use App\Enums\NewStatusEnum;
use App\Models\News;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Placeholder::make('order_status')
                    ->label('Estado de la Portada')
                    ->content(function () {
                        $orders = News::whereBetween('sort_order', [1, 5])->pluck('sort_order')->toArray();
                        $missing = array_diff([1, 2, 3, 4, 5], $orders);

                        if (empty($missing)) {
                            return new HtmlString('<span class="text-success-600 font-bold">✅ Todas las posiciones de portada están cubiertas.</span>');
                        }

                        $list = implode(', ', $missing);
                        return new HtmlString("
            <div class='p-3 bg-warning-50 border-l-4 border-warning-400 text-warning-700'>
                <p class='font-bold'>⚠️ Aviso: Portada Incompleta</p>
                <p class='text-sm'>Faltan noticias asignadas a las posiciones: <strong>{$list}</strong>.</p>
            </div>
        ");
                    })
                    ->columnSpanFull(),
                Group::make()->schema([
                    TextInput::make('title')
                        ->label('Tìtulo')
                        ->required()
                        ->maxLength(150)
                        ->rules([
                            fn($record) => function (string $attribute, $value, $fail) use ($record) {
                                $exists = News::whereRaw('LOWER(title) = ?', [strtolower($value)])
                                    ->when($record, fn($query) => $query->where('id', '!=', $record->id))
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
                        ])->columnSpanFull(),

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

                ])->columns(2),
                Group::make()->schema([
                    Select::make('category_id')
                        ->relationship('category', 'name')
                        ->label('Categoría')
                        ->required(),
                    // Toggle::make('featured')
                    //     ->label('¿Destacada?')
                    //     ->default(false)
                    //     ->required()
                    //     ->inline(false),
                    Select::make('sort_order')
                        ->label('Posición en Inicio')
                        ->options([
                            0 => 'No mostrar en el Top 5',
                            1 => '1 - Principal (Grande)',
                            2 => '2 - Secundaria superior',
                            3 => '3 - Secundaria superior',
                            4 => '4 - Secundaria inferior',
                            5 => '5 - Secundaria inferior',
                        ])
                        ->default(0)
                        ->selectablePlaceholder(false)
                        ->hint('Si eliges una posición ocupada, las demás se desplazarán.'),
                    FileUpload::make('featured_image')
                        ->label('Imagen Destacada')
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
                        ->directory('news')
                        ->visibility('public')
                        ->maxSize(51200) // 50MB (aumentado para videos)
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $sanitizedName = Str::slug($originalName);
                            $extension = $file->getClientOriginalExtension();

                            return time() . '_' . $sanitizedName . '.' . $extension;
                        })
                        ->columnSpanFull()
                        ->helperText('Tamaño máximo: 50MB. Formatos: JPG, PNG, WebP, GIF, MP4, MOV, AVI, WebM, MKV'),

                ])->columns(2),
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
