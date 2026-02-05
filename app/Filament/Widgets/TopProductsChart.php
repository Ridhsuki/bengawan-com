<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopProductsChart extends ChartWidget
{
    protected ?string $heading = 'Top 5 Produk Paling Menguntungkan';

    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = '2';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $topProducts = Sale::query()
            ->join('products', 'sales.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sales.total_profit) as total_cuan'))
            ->groupBy('products.name')
            ->orderByDesc('total_cuan')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Keuntungan (Rp)',
                    'data' => $topProducts->pluck('total_cuan'),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#6366f1',
                        '#8b5cf6',
                        '#a855f7',
                        '#d946ef',
                    ],
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $topProducts->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'callback' => "(value) => 'Rp ' + value.toLocaleString()",
                    ],
                ],
            ],
        ];
    }
}
