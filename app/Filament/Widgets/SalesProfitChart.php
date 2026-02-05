<?php

namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesProfitChart extends ChartWidget
{
    protected ?string $heading = 'Omset & Keuntungan (30 Hari)';

    protected static ?int $sort = 9;

    protected int|string|array $columnSpan = '2';

    protected function getData(): array
    {
        $data = Sale::query()
            ->selectRaw('DATE(transaction_date) as date, SUM(selling_price * quantity) as omset, SUM(total_profit) as profit')
            ->where('transaction_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $data->pluck('date')->map(fn($date) => Carbon::parse($date)->format('d M'));
        $omset = $data->pluck('omset');
        $profit = $data->pluck('profit');

        return [
            'datasets' => [
                [
                    'label' => 'Total Omset',
                    'data' => $omset,
                    'borderColor' => '#9ca3af',
                    'backgroundColor' => 'rgba(156, 163, 175, 0.1)',
                    'fill' => true,
                ],
                [
                    'label' => 'Keuntungan Bersih',
                    'data' => $profit,
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $dates,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
