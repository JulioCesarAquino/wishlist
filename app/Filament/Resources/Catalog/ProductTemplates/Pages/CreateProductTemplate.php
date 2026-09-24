<?php

namespace App\Filament\Resources\Catalog\ProductTemplates\Pages;

use App\Filament\Resources\Catalog\ProductTemplates\ProductTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductTemplate extends CreateRecord
{
    protected static string $resource = ProductTemplateResource::class;
}
