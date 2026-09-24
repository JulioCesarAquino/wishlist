<?php

namespace App\Filament\Resources\Events\Events;

use App\Filament\Resources\Events\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Events\RelationManagers\GuestsRelationManager;
use App\Filament\Resources\Events\Events\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\Events\Events\Schemas\EventForm;
use App\Filament\Resources\Events\Events\Tables\EventsTable;
use App\Models\Events\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'evento';

    protected static ?string $pluralModelLabel = 'Eventos';

    protected static ?string $slug = 'events';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (! auth()->user()?->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
            GuestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
