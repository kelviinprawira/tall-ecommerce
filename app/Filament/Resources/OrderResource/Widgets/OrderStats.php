<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Faker\Core\Number;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', Order::where('status', 'new')->count()),
            Stat::make('Order Processing', Order::where('status', 'processing')->count()),
            Stat::make('Order Shipped', Order::where('status', 'shipped')->count()),
            Stat::make('Order Delivered', Order::where('status', 'delivered')->count()),
            Stat::make('Order Canceled', Order::where('status', 'canceled')->count()),
            Stat::make('Average Price Order', \Illuminate\Support\Number::currency(Order::query()->avg('grand_total'),
                'IDR')),
            Stat::make('Total Price Order', \Illuminate\Support\Number::currency(Order::query()->sum('grand_total'), 'IDR'))
        ];
    }
}
