<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Sale;
use App\Models\ShopeeOrder;
use App\Models\ShopeeOrderItem;
use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

class SyncShopeeOrderJob implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public int $tries = 3;

    public function __construct(
        public int $shopeeShopId,
        public string $orderSn
    ) {
    }

    public function uniqueId(): string
    {
        return $this->shopeeShopId . '-' . $this->orderSn;
    }

    public function handle(ShopeeClient $client): void
    {
        $shop = ShopeeShop::findOrFail($this->shopeeShopId);

        $response = $client->getOrderDetail($shop, [$this->orderSn]);

        $detail = data_get($response, 'response.order_list.0')
            ?? data_get($response, 'order_list.0');

        if (!$detail) {
            return;
        }

        $status = data_get($detail, 'order_status');
        $items = data_get($detail, 'item_list', []);

        DB::transaction(function () use ($shop, $detail, $status, $items) {
            $order = ShopeeOrder::updateOrCreate(
                ['order_sn' => $this->orderSn],
                [
                    'shopee_shop_id' => $shop->id,
                    'order_status' => $status,
                    'raw_payload' => $detail,
                ]
            );

            foreach ($items as $item) {
                $itemId = (int) data_get($item, 'item_id');
                $modelId = (int) (data_get($item, 'model_id') ?? 0);

                $qty = (int) (
                    data_get($item, 'model_quantity_purchased')
                    ?? data_get($item, 'item_quantity')
                    ?? data_get($item, 'quantity')
                    ?? 1
                );

                $quantity = max(1, $qty);

                $sku = data_get($item, 'model_sku') ?: data_get($item, 'item_sku');

                $unitPrice = data_get($item, 'model_discounted_price')
                    ?? data_get($item, 'model_original_price')
                    ?? data_get($item, 'item_price')
                    ?? data_get($item, 'original_price')
                    ?? 0;

                $product = Product::where('shopee_shop_id', $shop->id)
                    ->where('shopee_item_id', $itemId)
                    ->where('shopee_model_id', $modelId)
                    ->lockForUpdate()
                    ->first();

                ShopeeOrderItem::updateOrCreate(
                    [
                        'shopee_order_id' => $order->id,
                        'shopee_item_id' => $itemId,
                        'shopee_model_id' => $modelId,
                    ],
                    [
                        'product_id' => $product?->id,
                        'shopee_sku' => $sku,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'raw_payload' => $item,
                    ]
                );

                Sale::updateOrCreate(
                    [
                        'sales_channel' => 'shopee',
                        'external_order_sn' => $this->orderSn,
                        'external_item_id' => $itemId,
                        'external_model_id' => $modelId,
                        'product_id' => $product?->id,
                    ],
                    [
                        'quantity' => $quantity,
                        'cost_price' => $product?->cost_price ?? 0,
                        'selling_price' => $unitPrice ?? 0,
                        'negotiated_price' => $unitPrice ?? 0,
                        'total_profit' => $product
                            ? (($unitPrice ?? 0) - (float) $product->cost_price) * $quantity
                            : 0,
                        'customer_info' => 'Shopee Order ' . $this->orderSn,
                        'transaction_date' => now(),
                        'external_status' => $status,
                        'external_payload' => $item,
                        'external_synced_at' => now(),
                    ]
                );

                if ($product && $this->shouldReduceStock($status) && blank($order->stock_applied_at)) {
                    $newStock = max(0, ((int) $product->stock) - $quantity);

                    $product->forceFill([
                        'stock' => $newStock,
                        'shopee_stock' => $newStock,
                        'shopee_last_synced_at' => now(),
                        'shopee_sync_status' => 'success',
                        'shopee_sync_error' => null,
                    ])->save();
                }

                if ($product && $this->shouldRestoreStock($status) && filled($order->stock_applied_at) && blank($order->stock_restored_at)) {
                    $newStock = ((int) $product->stock) + $quantity;

                    $product->forceFill([
                        'stock' => $newStock,
                        'shopee_stock' => $newStock,
                        'shopee_last_synced_at' => now(),
                        'shopee_sync_status' => 'restored',
                        'shopee_sync_error' => null,
                    ])->save();
                }
            }

            if ($this->shouldReduceStock($status) && blank($order->stock_applied_at)) {
                $order->forceFill(['stock_applied_at' => now()])->save();
            }

            if ($this->shouldRestoreStock($status) && filled($order->stock_applied_at) && blank($order->stock_restored_at)) {
                $order->forceFill(['stock_restored_at' => now()])->save();
            }
        });
    }

    private function shouldReduceStock(?string $status): bool
    {
        return in_array($status, [
            'READY_TO_SHIP',
            'PROCESSED',
            'SHIPPED',
            'TO_CONFIRM_RECEIVE',
            'COMPLETED',
        ], true);
    }

    private function shouldRestoreStock(?string $status): bool
    {
        return in_array($status, [
            'CANCELLED',
        ], true);
    }
}
