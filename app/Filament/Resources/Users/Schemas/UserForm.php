<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información Personal')
                    ->description('Datos básicos del usuario')
                    ->schema([
                        Group::make()->schema([
                            TextInput::make('name')
                                ->label('Nombre Completo')
                                ->required()
                                ->maxLength(255)
                                ->placeholder('Ej: Juan Pérez García'),

                            TextInput::make('email')
                                ->label('Correo Electrónico')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->placeholder('usuario@ejemplo.com'),
                        ]),

                        Group::make()->schema([
                            TextInput::make('password')
                                ->label('Contraseña')
                                ->password()
                                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn (string $context): bool => $context === 'create')
                                ->maxLength(255)
                                ->revealable()
                                ->placeholder('Mínimo 8 caracteres')
                                ->helperText(fn (string $context): string => $context === 'edit'
                                        ? 'Dejar en blanco para mantener la contraseña actual'
                                        : 'Ingresa una contraseña segura de al menos 8 caracteres'
                                ),
                        ]),
                    ]),

                Section::make('Roles y Permisos')
                    ->description('Asigna uno o más roles al usuario')
                    ->schema([
                        CheckboxList::make('roles')
                            ->label('Roles del Usuario')
                            ->relationship('roles', 'name')
                            ->options(Role::all()->pluck('name', 'id'))  // ✅ CAMBIO AQUÍ: usar 'id' en lugar de 'name'
                            ->descriptions([
                                'desarrollador' => 'Acceso total al sistema incluyendo gestión de usuarios',
                                'administrador' => 'Gestión completa de contenido y publicidad',
                                'colaborador' => 'Creación y edición de noticias y galerías',
                            ])
                            ->columns(3)
                            ->gridDirection('row')
                            ->required()
                            ->helperText('El usuario debe tener al menos un rol asignado')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
