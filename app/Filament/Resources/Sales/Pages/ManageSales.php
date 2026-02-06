<?php

namespace App\Filament\Resources\Sales\Pages;

use App\Filament\Resources\Sales\SaleResource;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ManageRecords;
use App\Models\Sale;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;

class ManageSales extends ManageRecords
{
    protected static string $resource = SaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->label('Export PDF')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->modalHeading('Cetak Laporan Penjualan')
                ->modalDescription('Silakan pilih periode tanggal laporan yang ingin diunduh.')
                ->modalSubmitActionLabel('Download PDF')
                ->form([
                    DatePicker::make('start_date')
                        ->label('Dari Tanggal')
                        ->native(false)
                        ->closeOnDateSelection()
                        ->default(now()->startOfMonth())
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Sampai Tanggal')
                        ->native(false)
                        ->closeOnDateSelection()
                        ->default(now()->endOfMonth())
                        ->required()
                        ->afterOrEqual('start_date'),
                ])
                ->action(function (array $data) {
                    $setting = Setting::first();
                    $startDate = Carbon::parse($data['start_date']);
                    $endDate = Carbon::parse($data['end_date']);

                    $sales = Sale::query()
                        ->with('product')
                        ->whereDate('transaction_date', '>=', $startDate)
                        ->whereDate('transaction_date', '<=', $endDate)
                        ->orderBy('transaction_date', 'asc')
                        ->get();

                    if ($sales->isEmpty()) {
                        Notification::make()
                            ->title('Tidak ada data penjualan pada periode tersebut.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $totalOmset = $sales->sum(fn($s) => $s->selling_price * $s->quantity);
                    $totalProfit = $sales->sum('total_profit');

                    $dateRangeText = $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y');

                    $pdf = Pdf::loadView('pdf.sales-report', [
                        'sales' => $sales,
                        'totalOmset' => $totalOmset,
                        'totalProfit' => $totalProfit,
                        'dateRange' => $dateRangeText,
                        'setting' => $setting,
                    ])->setPaper('a4', 'portrait')->setOption('isPhpEnabled', true);

                    $output = $pdf->output();

                    return response()->streamDownload(
                        fn() => print ($output),
                        "laporan-penjualan-{$startDate->format('Ymd')}-{$endDate->format('Ymd')}.pdf"
                    );
                }),
        ];
    }
}
