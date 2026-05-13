<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ShopeeSyncLog;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Throwable;

class SyncProductStockToShopeeJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $productId)
    {
    }

    public function uniqueId(): string
    {
        return 'product-stock-' . $this->productId;
    }

    public function handle(ShopeeClient $client): void
    {
        $product = Product::with('shopeeShop')->find($this->productId);

        if (!$product || !$product->canSyncShopeeStock()) {
            return;
        }

        Cache::lock('sync-shopee-stock-' . $product->id, 60)->block(10, function () use ($client, $product) {
            try {
                $response = $client->updateStock(
                    shop: $product->shopeeShop,
                    itemId: (int) $product->shopee_item_id,
                    modelId: (int) $product->shopee_model_id,
                    stock: (int) $product->stock,
                );

                $product->forceFill([
                    'shopee_stock' => (int) $product->stock,
                    'shopee_last_synced_at' => now(),
                    'shopee_sync_status' => 'success',
                    'shopee_sync_error' => null,
                ])->saveQuietly();

                ShopeeSyncLog::create([
                    'product_id' => $product->id,
                    'shopee_shop_id' => $product->shopee_shop_id,
                    'type' => 'push_stock',
                    'status' => 'success',
                    'message' => 'Stock berhasil dikirim ke Shopee.',
                    'request_payload' => [
                        'item_id' => $product->shopee_item_id,
                        'model_id' => $product->shopee_model_id,
                        'stock' => $product->stock,
                    ],
                    'response_payload' => $response,
                ]);
            } catch (Throwable $e) {
                $product->forceFill([
                    'shopee_sync_status' => 'failed',
                    'shopee_sync_error' => $e->getMessage(),
                ])->saveQuietly();

                ShopeeSyncLog::create([
                    'product_id' => $product->id,
                    'shopee_shop_id' => $product->shopee_shop_id,
                    'type' => 'push_stock',
                    'status' => 'failed',
                    'message' => $e->getMessage(),
                    'request_payload' => [
                        'item_id' => $product->shopee_item_id,
                        'model_id' => $product->shopee_model_id,
                        'stock' => $product->stock,
                    ],
                ]);

                throw $e;
            }
        });
    }
}
