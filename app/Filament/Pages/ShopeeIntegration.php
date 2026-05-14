<?php

namespace App\Filament\Pages;

use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Throwable;
use BackedEnum;

class ShopeeIntegration extends Page
{
    protected static ?string $navigationLabel = 'Shopee Integration';

    protected static ?string $title = 'Shopee Integration';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-link';

    protected static string|\UnitEnum|null $navigationGroup = 'Marketplace';

    // protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.shopee-integration';

    public array $status = [];

    public ?string $lastTestMessage = null;

    public function mount(): void
    {
        $this->loadStatus();
    }

    public static function getNavigationBadge(): ?string
    {
        $shop = ShopeeShop::where('is_active', true)->latest('id')->first();

        if (! $shop) {
            return 'Action needed';
        }

        if ($shop->token_expires_at && now()->greaterThan($shop->token_expires_at)) {
            return 'Expired';
        }

        return 'Connected';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $shop = ShopeeShop::where('is_active', true)->latest('id')->first();

        if (! $shop) {
            return 'danger';
        }

        if ($shop->token_expires_at && now()->greaterThan($shop->token_expires_at)) {
            return 'warning';
        }

        return 'success';
    }

    public function loadStatus(): void
    {
        $shop = ShopeeShop::where('is_active', true)
            ->latest('id')
            ->first();

        $tokenStatus = 'not_connected';

        if ($shop) {
            if ($shop->token_expires_at && now()->greaterThan($shop->token_expires_at)) {
                $tokenStatus = 'expired';
            } elseif ($shop->token_expires_at && now()->diffInMinutes($shop->token_expires_at, false) <= 60) {
                $tokenStatus = 'expiring_soon';
            } else {
                $tokenStatus = 'active';
            }
        }

        $this->status = [
            'connected' => (bool) $shop,
            'token_status' => $tokenStatus,

            'shop_local_id' => $shop?->id,
            'shop_id' => $shop?->shop_id,
            'shop_name' => $shop?->shop_name,
            'token_expires_at' => $shop?->token_expires_at
                ? $shop->token_expires_at->format('d M Y H:i')
                : null,
            'is_active' => (bool) ($shop?->is_active ?? false),

            'app_url' => config('app.url'),
            'shopee_host' => config('shopee.host'),
            'partner_id' => config('shopee.partner_id'),
            'redirect_url' => config('shopee.redirect_url'),
            'webhook_verify' => (bool) config('shopee.webhook_verify'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('connect')
                ->label(fn () => $this->status['connected'] ?? false ? 'Re-authorize Shopee' : 'Hubungkan Shopee')
                ->icon('heroicon-o-link')
                ->color('success')
                ->url(fn () => route('shopee.connect')),

            Action::make('test_connection')
                ->label('Test Koneksi')
                ->icon('heroicon-o-signal')
                ->color('info')
                ->visible(fn () => (bool) ($this->status['connected'] ?? false))
                ->action(function (ShopeeClient $client) {
                    $shop = ShopeeShop::where('is_active', true)->latest('id')->first();

                    if (! $shop) {
                        Notification::make()
                            ->title('Shopee belum terhubung.')
                            ->danger()
                            ->send();

                        return;
                    }

                    try {
                        $response = $client->getItemList($shop);
                        $totalItems = count(data_get($response, 'response.item', []));

                        $this->lastTestMessage = "Koneksi berhasil. Item terbaca: {$totalItems}.";

                        Notification::make()
                            ->title('Koneksi Shopee berhasil.')
                            ->body($this->lastTestMessage)
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        $this->lastTestMessage = $e->getMessage();

                        Notification::make()
                            ->title('Koneksi Shopee gagal.')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }

                    $this->loadStatus();
                }),

            Action::make('refresh_token')
                ->label('Refresh Token')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => (bool) ($this->status['connected'] ?? false))
                ->requiresConfirmation()
                ->action(function (ShopeeClient $client) {
                    $shop = ShopeeShop::where('is_active', true)->latest('id')->first();

                    if (! $shop) {
                        Notification::make()
                            ->title('Shopee belum terhubung.')
                            ->danger()
                            ->send();

                        return;
                    }

                    try {
                        $client->refreshAccessToken($shop);

                        Notification::make()
                            ->title('Token Shopee berhasil diperbarui.')
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Gagal refresh token.')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }

                    $this->loadStatus();
                }),

            Action::make('deactivate')
                ->label('Nonaktifkan Koneksi Lokal')
                ->icon('heroicon-o-no-symbol')
                ->color('danger')
                ->visible(fn () => (bool) ($this->status['connected'] ?? false))
                ->requiresConfirmation()
                ->modalHeading('Nonaktifkan koneksi Shopee lokal?')
                ->modalDescription('Aksi ini hanya menonaktifkan koneksi di Bengawan. Ini tidak menghapus app atau toko dari Shopee Open Platform.')
                ->action(function () {
                    $shop = ShopeeShop::where('is_active', true)->latest('id')->first();

                    if ($shop) {
                        $shop->forceFill([
                            'is_active' => false,
                        ])->save();
                    }

                    Notification::make()
                        ->title('Koneksi Shopee lokal dinonaktifkan.')
                        ->success()
                        ->send();

                    $this->loadStatus();
                }),
        ];
    }
}
