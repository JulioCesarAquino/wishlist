<?php

namespace App\Filament\Resources\Identity\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->default(fn (): string => Str::password(12))
                    ->helperText('Deixe em branco ao editar para manter a senha atual. Ao criar, uma senha é sugerida automaticamente — copie e envie ao anfitrião.')
                    ->columnSpanFull(),
                Toggle::make('is_admin')
                    ->label('É administrador')
                    ->helperText('Administradores veem todos os eventos e o catálogo geral. Anfitriões veem só o próprio evento.')
                    ->default(false),
            ]);
    }
}
