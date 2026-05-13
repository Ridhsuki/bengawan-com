<?php

namespace App\Console\Commands;

use App\Models\ShopeeShop;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ShopeeClearCatalogCacheCommand extends Command
{
    protected $signature = 'shopee:clear-catalog-cache';

    protected $description = 'Clear cached Shopee catalog data.';

    public function handle(): int
    {
        ShopeeShop::query()->each(function (ShopeeShop $shop) {
            Cache::forget('shopee:categories:leaf:' . $shop->id);
            Cache::forget('shopee:logistics:' . $shop->id);
            Cache::forget('shopee:items:normal:' . $shop->id);
        });

        $this->info('Shopee catalog cache cleared.');

        return self::SUCCESS;
    }
}
