<?php

namespace App\Filament\Pages;

use App\Models\Sale;
use App\Models\ShopeeShop;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Throwable;
use UnitEnum;

class ShopeeSalesReport extends Page
{
    protected static ?string $navigationLabel = 'Shopee Sales Report';

    protected static ?string $title = 'Shopee Sales Report';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.shopee-sales-report';

    #[Url]
    public string $period = '30';

    #[Url]
    public string $status = 'all';

    public ?string $lastPullMessage = null;

    public function setPeriod(string $period): void
    {
        if (! in_array($period, ['7', '30', '90'], true)) {
            $period = '30';
        }

        $this->period = $period;

        $this->forgetComputedProperties();
    }

    public function setStatus(string $status): void
    {
        $this->status = blank($status) ? 'all' : $status;

        $this->forgetComputedProperties();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('pull_orders')
                ->label('Pull Order Shopee')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->modalHeading('Tarik order Shopee terbaru?')
                ->modalDescription('Sistem akan mengambil order Shopee berdasarkan update_time 24 jam terakhir dan memasukkannya ke queue.')
                ->action(function () {
                    $this->runPullOrders(sync: false);
                }),

            Action::make('pull_orders_sync')
                ->label('Pull & Sync Sekarang')
                ->icon('heroicon-o-bolt')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Tarik dan proses order sekarang?')
                ->modalDescription('Mode ini memproses order secara langsung. Gunakan untuk testing atau demo sandbox, bukan untuk order dalam jumlah besar.')
                ->action(function () {
                    $this->runPullOrders(sync: true);
                }),
        ];
    }

    protected function runPullOrders(bool $sync = false): void
    {
        $shop = $this->activeShopeeShop();

        if (! $shop) {
            $this->notifyShopeeNotConnected();

            return;
        }

        if (blank($shop->access_token) || blank($shop->refresh_token)) {
            Notification::make()
                ->title('Token Shopee tidak lengkap.')
                ->body('Hubungkan ulang toko Shopee melalui halaman Shopee Integration sebelum menarik order.')
                ->danger()
                ->send();

            return;
        }

        try {
            $parameters = [
                '--hours' => 24,
            ];

            if ($sync) {
                $parameters['--sync'] = true;
            }

            $exitCode = Artisan::call('shopee:pull-orders', $parameters);

            $this->lastPullMessage = trim(Artisan::output());

            $this->forgetComputedProperties();

            if ($exitCode !== 0) {
                Notification::make()
                    ->title('Pull order Shopee gagal.')
                    ->body($this->lastPullMessage ?: 'Command gagal dijalankan.')
                    ->danger()
                    ->send();

                return;
            }

            if ($sync) {
                Notification::make()
                    ->title('Order Shopee berhasil ditarik dan diproses.')
                    ->body('Data terbaru sudah dimuat ke laporan Shopee.')
                    ->success()
                    ->send();

                return;
            }

            Notification::make()
                ->title('Pull order Shopee dijalankan.')
                ->body('Order akan diproses oleh queue. Pastikan queue worker berjalan.')
                ->success()
                ->send();
        } catch (Throwable $e) {
            Notification::make()
                ->title($sync ? 'Gagal pull dan sync order Shopee.' : 'Gagal pull order Shopee.')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function activeShopeeShop(): ?ShopeeShop
    {
        return ShopeeShop::query()
            ->where('is_active', true)
            ->latest('id')
            ->first();
    }

    protected function notifyShopeeNotConnected(): void
    {
        Notification::make()
            ->title('Shopee belum terhubung.')
            ->body('Hubungkan toko Shopee terlebih dahulu melalui menu Marketplace → Shopee Integration, lalu ulangi proses pull order.')
            ->danger()
            ->send();
    }

    protected function forgetComputedProperties(): void
    {
        unset($this->summary);
        unset($this->statusSummary);
        unset($this->latestShopeeSales);
        unset($this->availableStatuses);
    }

    #[Computed]
    public function summary(): array
    {
        $query = $this->baseQueryForSummary();

        return [
            'total_orders' => (clone $query)
                ->whereNotNull('external_order_sn')
                ->distinct('external_order_sn')
                ->count('external_order_sn'),

            'total_items' => (int) (clone $query)
                ->sum('quantity'),

            'total_revenue' => (float) (clone $query)
                ->selectRaw('COALESCE(SUM(negotiated_price * quantity), 0) as total')
                ->value('total'),

            'total_profit' => (float) (clone $query)
                ->sum('total_profit'),
        ];
    }

    #[Computed]
    public function statusSummary()
    {
        return $this->baseQuery()
            ->select([
                'external_status',
                DB::raw('COUNT(DISTINCT external_order_sn) as total_orders'),
                DB::raw('COALESCE(SUM(quantity), 0) as total_items'),
                DB::raw('COALESCE(SUM(negotiated_price * quantity), 0) as total_revenue'),
            ])
            ->groupBy('external_status')
            ->orderByDesc('total_orders')
            ->get();
    }

    #[Computed]
    public function latestShopeeSales()
    {
        return $this->baseQuery()
            ->with('product')
            ->latest('transaction_date')
            ->latest('id')
            ->limit(30)
            ->get();
    }

    #[Computed]
    public function availableStatuses(): array
    {
        return Sale::query()
            ->where('sales_channel', 'shopee')
            ->whereNotNull('external_status')
            ->distinct()
            ->orderBy('external_status')
            ->pluck('external_status')
            ->filter()
            ->values()
            ->all();
    }

    #[Computed]
    public function isShopeeConnected(): bool
    {
        return (bool) $this->activeShopeeShop();
    }

    protected function baseQuery(): Builder
    {
        $days = max(1, (int) $this->period);

        return Sale::query()
            ->where('sales_channel', 'shopee')
            ->whereDate('transaction_date', '>=', now()->subDays($days))
            ->when($this->status !== 'all', function (Builder $query) {
                $query->where('external_status', $this->status);
            });
    }

    protected function baseQueryForSummary(): Builder
    {
        return $this->baseQuery()
            ->where(function (Builder $query) {
                $query->whereNull('external_status')
                    ->orWhereNotIn('external_status', [
                        'CANCELLED',
                        'cancelled',
                    ]);
            });
    }

    public function formatRupiah(float|int|null $value): string
    {
        return 'Rp' . number_format((float) $value, 0, ',', '.');
    }
}
