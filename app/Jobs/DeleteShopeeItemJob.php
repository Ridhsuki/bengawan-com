<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ShopeeShop;
use App\Models\ShopeeSyncLog;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class DeleteShopeeItemJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(
        public int $productId,
        public int $shopeeShopId,
        public int $itemId
    ) {
    }

    public function handle(ShopeeClient $client): void
    {
        $shop = ShopeeShop::findOrFail($this->shopeeShopId);
        $product = Product::find($this->productId);

        try {
            $response = $client->deleteItem($shop, $this->itemId);

            if ($product) {
                $product->forceFill([
                    'shopee_last_shop_id' => $product->shopee_shop_id,
                    'shopee_last_item_id' => $product->shopee_item_id,
                    'shopee_last_model_id' => $product->shopee_model_id,
                    'shopee_last_sku' => $product->shopee_sku,

                    'shopee_item_id' => null,
                    'shopee_model_id' => 0,
                    'shopee_sku' => null,
                    'sync_shopee_stock' => false,
                    'shopee_stock' => null,

                    'shopee_publish_status' => null,
                    'shopee_item_status' => null,
                    'shopee_deleted_at' => now(),
                    'shopee_sync_status' => null,
                    'shopee_sync_error' => null,
                    'shopee_unlinked_reason' => null,
                ])->saveQuietly();
            }

            ShopeeSyncLog::create([
                'product_id' => $this->productId,
                'shopee_shop_id' => $this->shopeeShopId,
                'type' => 'delete_item',
                'status' => 'success',
                'message' => 'Produk berhasil dihapus dari Shopee.',
                'request_payload' => [
                    'item_id' => $this->itemId,
                ],
                'response_payload' => $response,
            ]);
        } catch (Throwable $e) {
            if ($product) {
                $product->forceFill([
                    'shopee_sync_status' => 'failed',
                    'shopee_sync_error' => $e->getMessage(),
                ])->saveQuietly();
            }

            ShopeeSyncLog::create([
                'product_id' => $this->productId,
                'shopee_shop_id' => $this->shopeeShopId,
                'type' => 'delete_item',
                'status' => 'failed',
                'message' => $e->getMessage(),
                'request_payload' => [
                    'item_id' => $this->itemId,
                ],
            ]);

            throw $e;
        }
    }
}
