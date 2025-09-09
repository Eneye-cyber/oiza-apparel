<?php

namespace App\Filament\Resources\Products\Widgets;

use App\Models\Products\Product;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Products', Product::count())->description('Total product count'),
            Stat::make('Active Products', Product::active()->count())
                ->description('Currently available')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Inactive Products', Product::inactive()->count())
                ->description('Disabled or out of stock')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
