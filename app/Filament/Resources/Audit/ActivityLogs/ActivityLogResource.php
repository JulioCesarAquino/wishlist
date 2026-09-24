<?php

namespace App\Filament\Resources\Audit\ActivityLogs;

use App\Filament\Resources\Audit\ActivityLogs\Pages\ListActivityLogs;
use App\Filament\Resources\Audit\ActivityLogs\Tables\ActivityLogsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $modelLabel = 'registro';

    protected static ?string $pluralModelLabel = 'Auditoria';

    protected static ?string $navigationLabel = 'Auditoria';

    protected static ?string $slug = 'activity-logs';

    /**
     * Auditing is an admin-only concern — hosts don't get to see (or hide)
     * a trail of their own actions.
     */
    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }
}
