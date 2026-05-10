<?php

namespace App\Console\Commands;

use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Console\Command;
use Throwable;

class RefreshShopeeTokensCommand extends Command
{
    protected $signature = 'shopee:refresh-tokens';

    protected $description = 'Refresh Shopee access tokens before expiration.';

    public function handle(ShopeeClient $client): int
    {
        ShopeeShop::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('token_expires_at')
                    ->orWhere('token_expires_at', '<=', now()->addMinutes(30));
            })
            ->each(function (ShopeeShop $shop) use ($client) {
                try {
                    $client->refreshAccessToken($shop);
                    $this->info("Token refreshed for shop {$shop->shop_id}");
                } catch (Throwable $e) {
                    $shop->forceFill(['is_active' => false])->save();
                    $this->error("Failed refreshing shop {$shop->shop_id}: {$e->getMessage()}");
                }
            });

        return self::SUCCESS;
    }
}
