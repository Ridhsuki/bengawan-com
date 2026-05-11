<?php

namespace App\Console\Commands;

use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Console\Command;

class ShopeeListItemsCommand extends Command
{
    protected $signature = 'shopee:list-items {--status=NORMAL}';

    protected $description = 'List Shopee item IDs from authorized shop.';

    public function handle(ShopeeClient $client): int
    {
        $shop = ShopeeShop::where('is_active', true)->first();

        if (!$shop) {
            $this->error('Belum ada toko Shopee yang terhubung.');
            return self::FAILURE;
        }

        $response = $client->getItemList(
            shop: $shop,
            itemStatus: (string) $this->option('status')
        );

        $items = data_get($response, 'response.item', []);

        if (empty($items)) {
            $this->warn('Tidak ada produk Shopee ditemukan.');
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
            return self::SUCCESS;
        }

        foreach ($items as $item) {
            $this->line(json_encode($item, JSON_PRETTY_PRINT));
        }

        return self::SUCCESS;
    }
}
