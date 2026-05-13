<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Console\Command;
use Throwable;

class ShopeeReconcileProductsCommand extends Command
{
    protected $signature = 'shopee:reconcile-products';

    protected $description = 'Check mapped Shopee products and mark missing/deleted items.';

    public function handle(ShopeeClient $client): int
    {
        $shops = ShopeeShop::where('is_active', true)->get();

        foreach ($shops as $shop) {
            Product::query()
                ->where('shopee_shop_id', $shop->id)
                ->whereNotNull('shopee_item_id')
                ->whereNotIn('shopee_item_status', ['deleted', 'not_found'])
                ->select('id', 'name', 'shopee_item_id', 'shopee_shop_id')
                ->chunkById(30, function ($products) use ($client, $shop) {
                    $itemIds = $products->pluck('shopee_item_id')->filter()->unique()->values()->all();

                    if (empty($itemIds)) {
                        return;
                    }

                    try {
                        $response = $client->getItemBaseInfo($shop, $itemIds);

                        $foundIds = collect(data_get($response, 'response.item_list', []))
                            ->pluck('item_id')
                            ->map(fn ($id) => (int) $id)
                            ->all();

                        foreach ($products as $product) {
                            if (! in_array((int) $product->shopee_item_id, $foundIds, true)) {
                                $product->forceFill([
                                    'sync_shopee_stock' => false,
                                    'shopee_publish_status' => 'not_found',
                                    'shopee_item_status' => 'not_found',
                                    'shopee_last_checked_at' => now(),
                                    'shopee_unlinked_reason' => 'Item tidak ditemukan saat rekonsiliasi dengan Shopee.',
                                ])->saveQuietly();

                                $this->warn("Missing Shopee item: {$product->name}");
                            } else {
                                $product->forceFill([
                                    'shopee_item_status' => 'normal',
                                    'shopee_last_checked_at' => now(),
                                    'shopee_unlinked_reason' => null,
                                ])->saveQuietly();
                            }
                        }
                    } catch (Throwable $e) {
                        $this->error($e->getMessage());
                    }
                });
        }

        return self::SUCCESS;
    }
}
