<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                // ✅ LADO IZQUIERDO
                                Group::make()
                                    ->schema([
                                        // Renglón 1: Name (completo)
                                        TextInput::make('name')
                                            ->label('Categoría')
                                            ->required()
                                            ->maxLength(50)
                                            ->rules([
                                                fn ($record) => function (string $attribute, $value, $fail) use ($record) {
                                                    $exists = Category::whereRaw('LOWER(name) = ?', [strtolower($value)])
                                                        ->when($record, fn ($query) => $query->where('id', '!=', $record->id))
                                                        ->exists();

                                                    if ($exists) {
                                                        $fail('Esta categoría ya existe (sin importar mayúsculas/minúsculas).');
                                                    }
                                                },
                                            ])
                                            ->validationMessages([
                                                'required' => 'El nombre de la categoría es obligatorio.',
                                                'max' => 'El nombre no puede superar :max caracteres.',
                                            ])
                                            ->afterStateUpdated(function ($state, callable $set) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->live(onBlur: true)
                                            ->columnSpanFull(),

                                        // Renglón 2: Order y is_active
                                        Group::make()
                                            ->schema([
                                                TextInput::make('order')
                                                    ->label('Orden')
                                                    ->required()
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0),

                                                Toggle::make('is_active')
                                                    ->label('¿Activo?')
                                                    ->default(true)
                                                    ->required()
                                                    ->inline(false),
                                            ])
                                            ->columns(2)
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpan(1),

                                // ✅ LADO DERECHO: Description (misma altura)
                                Textarea::make('description')
                                    ->label('Descripción')
                                    ->maxLength(500)
                                    ->rows(5)
                                    ->placeholder('Descripción de la categoría...')
                                    ->columnSpan(1),
                            ])
                            ->columns(2),

                        // ✅ CAMPO OCULTO: Slug
                        TextInput::make('slug')
                            ->required()
                            ->hidden()
                            ->dehydrated(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
