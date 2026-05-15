<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\ShopeeSyncLog;
use App\Services\Shopee\ShopeeClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PublishProductToShopeeJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public function __construct(public int $productId)
    {
    }

    public function handle(ShopeeClient $client): void
    {
        $product = Product::with('shopeeShop')->find($this->productId);

        if (!$product || !$product->shopeeShop) {
            return;
        }

        if ($product->isPublishedToShopee()) {
            return;
        }

        if (!$product->canPublishToShopee()) {
            $product->forceFill([
                'shopee_publish_status' => 'failed',
                'shopee_publish_error' => 'Data produk untuk publish ke Shopee belum lengkap.',
            ])->saveQuietly();

            return;
        }

        $product->forceFill([
            'shopee_publish_status' => 'pending',
            'shopee_publish_error' => null,
            'shopee_sync_error' => null,
            'shopee_unlinked_reason' => null,
        ])->saveQuietly();

        try {
            $imagePath = Storage::disk('public')->path($product->image);

            if (!is_file($imagePath)) {
                throw new \RuntimeException('File gambar produk tidak ditemukan: ' . $imagePath);
            }

            $uploadResponse = $client->uploadImage($product->shopeeShop, $imagePath);

            $imageId = data_get($uploadResponse, 'response.image_info.image_id')
                ?? data_get($uploadResponse, 'response.image_id')
                ?? data_get($uploadResponse, 'image_info.image_id')
                ?? data_get($uploadResponse, 'image_id');

            if (blank($imageId)) {
                throw new \RuntimeException('Upload image berhasil dipanggil, tetapi image_id tidak ditemukan: ' . json_encode($uploadResponse));
            }

            $payload = [
                'item_name' => str($product->name)->limit(120, '')->toString(),
                'description' => $product->description ?: $product->name,
                'category_id' => (int) $product->shopee_category_id,

                'brand' => [
                    'brand_id' => (int) ($product->shopee_brand_id ?? 0),
                    'original_brand_name' => $product->shopee_brand_name ?: 'NoBrand',
                ],

                'item_sku' => $product->shopee_sku ?: $product->serial_number ?: ('BGW-' . $product->id),
                'condition' => $product->shopee_condition ?: 'NEW',

                'original_price' => (float) ($product->discount_price ?: $product->price),

                'seller_stock' => [
                    [
                        'stock' => max(0, (int) $product->stock),
                    ],
                ],

                'weight' => (float) $product->shopee_weight,

                'dimension' => [
                    'package_length' => (int) $product->shopee_package_length,
                    'package_width' => (int) $product->shopee_package_width,
                    'package_height' => (int) $product->shopee_package_height,
                ],

                'logistic_info' => [
                    [
                        'logistic_id' => (int) $product->shopee_logistic_id,
                        'enabled' => true,
                    ],
                ],

                'image' => [
                    'image_id_list' => [$imageId],
                ],

                'pre_order' => [
                    'is_pre_order' => false,
                    'days_to_ship' => 2,
                ],
            ];

            $response = $client->addItem($product->shopeeShop, $payload);

            $itemId = data_get($response, 'response.item_id');

            if (blank($itemId)) {
                throw new \RuntimeException('Produk berhasil dikirim, tetapi item_id tidak ditemukan: ' . json_encode($response));
            }

            $product->forceFill([
                'shopee_item_id' => (int) $itemId,
                'shopee_model_id' => 0,
                'sync_shopee_stock' => true,
                'shopee_stock' => (int) $product->stock,
                'shopee_last_synced_at' => now(),
                'shopee_publish_status' => 'success',
                'shopee_publish_error' => null,
                'shopee_published_at' => now(),
                'shopee_item_status' => 'normal',
                'shopee_deleted_at' => null,
                'shopee_unlinked_reason' => null,
                'shopee_sync_status' => 'success',
                'shopee_sync_error' => null,
            ])->saveQuietly();

            ShopeeSyncLog::create([
                'product_id' => $product->id,
                'shopee_shop_id' => $product->shopee_shop_id,
                'type' => 'publish_product',
                'status' => 'success',
                'message' => 'Produk berhasil dipublish ke Shopee.',
                'request_payload' => $payload,
                'response_payload' => $response,
            ]);
        } catch (Throwable $e) {
            $product->forceFill([
                'shopee_publish_status' => 'failed',
                'shopee_publish_error' => $e->getMessage(),
            ])->saveQuietly();

            ShopeeSyncLog::create([
                'product_id' => $product->id,
                'shopee_shop_id' => $product->shopee_shop_id,
                'type' => 'publish_product',
                'status' => 'failed',
                'message' => $e->getMessage(),
                'request_payload' => $payload ?? null,
            ]);

            throw $e;
        }
    }
}
