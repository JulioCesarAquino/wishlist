<?php

namespace App\Filament\Resources\Orders\Orders\Pages;

use App\Filament\Resources\Orders\Orders\OrderResource;
use App\Filament\Widgets\OrdersOverviewWidget;
use App\Filament\Widgets\PaymentMethodsChartWidget;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static ?string $title = 'Pedidos';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrdersOverviewWidget::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            PaymentMethodsChartWidget::class,
        ];
    }
}
