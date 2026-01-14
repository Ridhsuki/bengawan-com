<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\ChartWidget;

class StockStatusChart extends ChartWidget
{
    protected ?string $heading = 'Inventory Health / Status Stok';

    protected static ?int $sort = 3;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $outOfStock = Product::where('stock', '<=', 0)->count();

        $lowStock = Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->count();

        $safeStock = Product::where('stock', '>', 5)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => [$safeStock, $lowStock, $outOfStock],
                    'backgroundColor' => [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                    ],
                ],
            ],
            'labels' => ['In Stock (Aman)', 'Low Stock (Menipis)', 'Out of Stock (Habis)'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
