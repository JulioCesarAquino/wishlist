<?php

namespace App\Filament\Resources\Catalog\ProductTemplates\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required(),
                Select::make('category')
                    ->label('Categoria')
                    ->options([
                        'Cozinha' => 'Cozinha',
                        'Casa' => 'Casa',
                        'Eletrônicos' => 'Eletrônicos',
                        'Enxoval' => 'Enxoval',
                        'Contribuição' => 'Contribuição',
                        'Decoração' => 'Decoração',
                    ])
                    ->searchable(),
                Textarea::make('description')
                    ->label('Descrição')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Imagem')
                    ->image()
                    ->disk('public')
                    ->imageEditor()
                    ->imagePreviewHeight('160')
                    ->directory('product-templates')
                    ->helperText('Opcional. Se não enviar, uma imagem genérica será usada.'),
                TextInput::make('suggested_price')
                    ->label('Preço sugerido')
                    ->numeric()
                    ->prefix('R$'),
            ]);
    }
}
