<?php

namespace App\Filament\Resources\Audit\ActivityLogs\Pages;

use App\Filament\Resources\Audit\ActivityLogs\ActivityLogResource;
use Filament\Resources\Pages\ListRecords;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected static ?string $title = 'Auditoria';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
