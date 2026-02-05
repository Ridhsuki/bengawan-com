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
            Stat::make('Total Produk', Product::count())
                ->description('Semua produk terdaftar')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Produk Aktif', Product::where('is_active', true)->count())
                ->description('Tersedia di katalog/toko')
                ->descriptionIcon('heroicon-m-eye')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Produk promo', Product::where('discount_price', '>', 0)->count())
                ->description('Sedang dalam masa diskon')
                ->descriptionIcon('heroicon-m-tag')
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Kategori', Category::count())
                ->description('Pengelompokan produk')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}
