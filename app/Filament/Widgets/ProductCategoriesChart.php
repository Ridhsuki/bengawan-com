<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\ChartWidget;

class ProductCategoriesChart extends ChartWidget
{
    protected ?string $heading = 'Product Distribution by Category';

    protected static ?int $sort = 2;

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Category::withCount('products')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Products',
                    'data' => $data->pluck('products_count'),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#ef4444',
                        '#10b981',
                        '#f59e0b',
                        '#6366f1',
                        '#8b5cf6',
                        '#ec4899',
                        '#14b8a6',
                        '#f97316',
                        '#84cc16'
                    ],
                ],
            ],
            'labels' => $data->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
