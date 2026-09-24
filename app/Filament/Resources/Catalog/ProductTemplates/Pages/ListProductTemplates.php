<?php

namespace App\Filament\Resources\Catalog\ProductTemplates\Pages;

use App\Filament\Resources\Catalog\ProductTemplates\ProductTemplateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductTemplates extends ListRecords
{
    protected static string $resource = ProductTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
