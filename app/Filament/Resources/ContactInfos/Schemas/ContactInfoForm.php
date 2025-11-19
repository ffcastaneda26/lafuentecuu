<?php

namespace App\Filament\Resources\ContactInfos\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ContactInfoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(150)
                            ->columnSpanFull(),
                        TextInput::make('phone')
                            ->tel()
                            ->label('Teléfono')
                            ->maxLength(20),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->email(),
                        Textarea::make('Dirección Completa')
                            ->columnSpanFull(),
                    ])->columns(2),

                    Section::make()->schema([

                    ]),
                ]),
                Group::make()->schema([
                    TextInput::make('social_facebook')
                        ->label('Facebook'),
                    TextInput::make('social_instagram')
                        ->label('Instagram'),
                    TextInput::make('social_tiktok')
                        ->label('Tik Tok'),
                    TextInput::make('social_twitter')
                        ->label('Twitter o X'),
                    TextInput::make('social_youtube')
                        ->label('You Tube'),
                    FileUpload::make('logo')
                        ->label('Logotipo')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                        ->disk('public') // ✅ Disco público
                        ->visibility('public') // ✅ IMPORTANTE: Visibilidad pública
                        ->maxSize(5120) // 5MB
                        ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                            $sanitizedName = Str::slug($originalName);
                            $extension = $file->getClientOriginalExtension();

                            return time().'_'.$sanitizedName.'.'.$extension;
                        })
                        ->helperText('Tamaño máximo: 5MB. Formatos: JPG, PNG, WebP')
                        ->columnSpanFull(),
                ])->columns(2),

                RichEditor::make('about_text')
                    ->label('Acerca de Nosotros')
                    ->toolbarButtons([
                        ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                        ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                        ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                        ['table', 'attachFiles'], // The `customBlocks` and `mergeTags` tools are also added here if those features are used.
                        ['undo', 'redo'],
                    ])
                    ->columnSpanFull(),

            ]);
    }
}
