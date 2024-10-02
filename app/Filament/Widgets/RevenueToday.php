<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueToday extends BaseWidget
{
    protected function getStats(): array
    {

        $totalRevenue = Order::whereDate('created_at', date('Y-m-d'))->sum('total') /100 ;


        return [
            Stat::make('Revenue Today (USD)' ,number_format($totalRevenue, 2, '.', ' '))

        ];
    }
}
