<?php

namespace App\Filament\Resources\Guests\Guests\Pages;

use App\Filament\Resources\Guests\Guests\GuestResource;
use App\Filament\Widgets\GuestsOverviewWidget;
use Filament\Resources\Pages\ListRecords;

class ListGuests extends ListRecords
{
    protected static string $resource = GuestResource::class;

    protected static ?string $title = 'Convidados';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            GuestsOverviewWidget::class,
        ];
    }
}
