<?php

namespace App\Filament\Resources\Identity\Users;

use App\Filament\Resources\Identity\Users\Pages\CreateUser;
use App\Filament\Resources\Identity\Users\Pages\EditUser;
use App\Filament\Resources\Identity\Users\Pages\ListUsers;
use App\Filament\Resources\Identity\Users\Schemas\UserForm;
use App\Filament\Resources\Identity\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $slug = 'users';

    protected static ?string $modelLabel = 'usuário';

    protected static ?string $pluralModelLabel = 'Usuários';

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
