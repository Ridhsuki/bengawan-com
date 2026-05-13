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
                    'sync_shopee_stock' => false,
                    'shopee_publish_status' => 'deleted',
                    'shopee_item_status' => 'deleted',
                    'shopee_deleted_at' => now(),
                    'shopee_sync_status' => 'deleted',
                    'shopee_sync_error' => null,
                    'shopee_unlinked_reason' => 'Produk dihapus dari Shopee melalui Bengawan.',
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
