<?php

namespace App\Console\Commands;

use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Console\Command;

class ShopeeListModelsCommand extends Command
{
    protected $signature = 'shopee:list-models {item_id}';

    protected $description = 'List model IDs for Shopee item.';

    public function handle(ShopeeClient $client): int
    {
        $shop = ShopeeShop::where('is_active', true)->first();

        if (! $shop) {
            $this->error('Belum ada toko Shopee yang terhubung.');
            return self::FAILURE;
        }

        $response = $client->getModelList($shop, (int) $this->argument('item_id'));

        $this->line(json_encode($response, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
