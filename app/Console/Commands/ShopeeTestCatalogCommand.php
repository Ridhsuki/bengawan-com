<?php

namespace App\Console\Commands;

use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeCatalogService;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Console\Command;
use Throwable;

class ShopeeTestCatalogCommand extends Command
{
    protected $signature = 'shopee:test-catalog {category_id?}';

    protected $description = 'Test Shopee catalog API responses.';

    public function handle(ShopeeClient $client, ShopeeCatalogService $catalog): int
    {
        $shop = ShopeeShop::where('is_active', true)->first();

        if (! $shop) {
            $this->error('Belum ada toko Shopee aktif.');
            return self::FAILURE;
        }

        $this->info('Shop: ' . $shop->shop_id);

        try {
            $categories = $catalog->categoryOptions('laptop');
            $this->info('Category options:');
            $this->line(json_encode($categories, JSON_PRETTY_PRINT));
        } catch (Throwable $e) {
            $this->error('Category error: ' . $e->getMessage());
        }

        $categoryId = (int) ($this->argument('category_id') ?: 300046);

        try {
            $brands = $catalog->brandOptions($categoryId);
            $this->info('Brand options:');
            $this->line(json_encode($brands, JSON_PRETTY_PRINT));
        } catch (Throwable $e) {
            $this->error('Brand error: ' . $e->getMessage());
        }

        try {
            $logistics = $catalog->logisticOptions();
            $this->info('Logistic options:');
            $this->line(json_encode($logistics, JSON_PRETTY_PRINT));
        } catch (Throwable $e) {
            $this->error('Logistic error: ' . $e->getMessage());
        }

        try {
            $items = $catalog->itemOptions();
            $this->info('Item options:');
            $this->line(json_encode($items, JSON_PRETTY_PRINT));
        } catch (Throwable $e) {
            $this->error('Item error: ' . $e->getMessage());
        }

        return self::SUCCESS;
    }
}
