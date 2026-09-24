<?php

namespace App\Filament\Resources\Catalog\ProductTemplates;

use App\Filament\Resources\Catalog\ProductTemplates\Pages\CreateProductTemplate;
use App\Filament\Resources\Catalog\ProductTemplates\Pages\EditProductTemplate;
use App\Filament\Resources\Catalog\ProductTemplates\Pages\ListProductTemplates;
use App\Filament\Resources\Catalog\ProductTemplates\Schemas\ProductTemplateForm;
use App\Filament\Resources\Catalog\ProductTemplates\Tables\ProductTemplatesTable;
use App\Models\Catalog\ProductTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductTemplateResource extends Resource
{
    protected static ?string $model = ProductTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $modelLabel = 'item do catálogo';

    protected static ?string $pluralModelLabel = 'Catálogo geral';

    protected static ?string $slug = 'catalog';

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return ProductTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductTemplatesTable::configure($table);
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
            'index' => ListProductTemplates::route('/'),
            'create' => CreateProductTemplate::route('/create'),
            'edit' => EditProductTemplate::route('/{record}/edit'),
        ];
    }
}
