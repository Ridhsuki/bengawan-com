<?php

namespace App\Console\Commands;

use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeCatalogService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ShopeeWarmCatalogCacheCommand extends Command
{
    protected $signature = 'shopee:warm-catalog {--clear}';

    protected $description = 'Warm Shopee category, logistic, and item cache for Filament dropdowns.';

    public function handle(ShopeeCatalogService $catalog): int
    {
        $shops = ShopeeShop::where('is_active', true)->get();

        if ($shops->isEmpty()) {
            $this->error('Belum ada toko Shopee aktif.');
            return self::FAILURE;
        }

        foreach ($shops as $shop) {
            if ($this->option('clear')) {
                Cache::forget('shopee:categories:leaf:' . $shop->id);
                Cache::forget('shopee:logistics:' . $shop->id);
                Cache::forget('shopee:items:normal:' . $shop->id);
            }

            $this->info("Warming catalog cache for shop {$shop->shop_id}...");

            $categories = $catalog->warmCategories($shop);
            $this->info('Categories cached: ' . count($categories));

            $logistics = $catalog->logisticOptions();
            $this->info('Logistics cached: ' . count($logistics));

            $items = $catalog->itemOptions();
            $this->info('Items cached: ' . count($items));
        }

        $this->info('Shopee catalog cache warmed.');

        return self::SUCCESS;
    }
}
