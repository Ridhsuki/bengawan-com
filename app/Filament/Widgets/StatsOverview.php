<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('All products in database')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Active Products', Product::where('is_active', true)->count())
                ->description('Products visible to customers')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Discounted Items', Product::where('discount_price', '>', 0)->count())
                ->description('Products currently on sale')
                ->descriptionIcon('heroicon-m-tag')
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Categories', Category::count())
                ->description('Product classifications')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
