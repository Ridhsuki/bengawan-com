<?php

namespace App\Services\Shopee;

use App\Models\ShopeeShop;
use Illuminate\Support\Facades\Cache;

class ShopeeCatalogService
{
    public function __construct(
        protected ShopeeClient $client
    ) {
    }

    public function activeShop(): ?ShopeeShop
    {
        return ShopeeShop::where('is_active', true)->first();
    }

    public function categoryOptions(?string $search = null): array
    {
        $shop = $this->activeShop();

        if (!$shop) {
            return [];
        }

        $categories = Cache::remember(
            'shopee:categories:leaf:' . $shop->id,
            now()->addHours(12),
            fn() => $this->loadLeafCategories($shop)
        );

        if (filled($search)) {
            $search = str($search)->lower()->toString();

            $categories = collect($categories)
                ->filter(fn(string $label) => str($label)->lower()->contains($search))
                ->take(50)
                ->all();
        }

        return collect($categories)
            ->take(50)
            ->all();
    }

    public function categoryLabel(?int $categoryId): ?string
    {
        if (!$categoryId) {
            return null;
        }

        return $this->categoryOptions()[$categoryId] ?? (string) $categoryId;
    }

    public function brandOptions(?int $categoryId, ?string $search = null): array
    {
        $shop = $this->activeShop();

        if (!$shop || !$categoryId) {
            return [
                0 => 'NoBrand',
            ];
        }

        $brands = Cache::remember(
            'shopee:brands:' . $shop->id . ':' . $categoryId,
            now()->addHours(12),
            function () use ($shop, $categoryId) {
                $response = $this->client->getBrandList($shop, $categoryId);

                $brands = collect(data_get($response, 'response.brand_list', []))
                    ->mapWithKeys(function (array $brand) {
                        $brandId = (int) data_get($brand, 'brand_id');

                        $name = data_get($brand, 'display_brand_name')
                            ?: data_get($brand, 'original_brand_name')
                            ?: data_get($brand, 'brand_name')
                            ?: ('Brand #' . $brandId);

                        return [$brandId => $name];
                    })
                    ->all();

                return [
                    0 => 'NoBrand',
                    ...$brands,
                ];
            }
        );

        if (filled($search)) {
            $search = str($search)->lower()->toString();

            $brands = collect($brands)
                ->filter(fn(string $label) => str($label)->lower()->contains($search))
                ->take(50)
                ->all();
        }

        return collect($brands)
            ->take(50)
            ->all();
    }

    public function brandLabel(?int $brandId, ?int $categoryId): ?string
    {
        if ($brandId === null) {
            return null;
        }

        return $this->brandOptions($categoryId)[$brandId] ?? (string) $brandId;
    }

    public function logisticOptions(): array
    {
        $shop = $this->activeShop();

        if (!$shop) {
            return [];
        }

        return Cache::remember(
            'shopee:logistics:' . $shop->id,
            now()->addHours(6),
            function () use ($shop) {
                $response = $this->client->getLogistics($shop);

                return collect(data_get($response, 'response.logistics_channel_list', []))
                    ->filter(fn(array $item) => (bool) data_get($item, 'enabled', true))
                    ->mapWithKeys(function (array $item) {
                        $id = (int) data_get($item, 'logistics_channel_id', data_get($item, 'logistic_id'));
                        $name = data_get($item, 'logistics_channel_name')
                            ?: data_get($item, 'logistic_name')
                            ?: ('Logistic #' . $id);

                        return [$id => $name . ' #' . $id];
                    })
                    ->all();
            }
        );
    }

    public function logisticLabel(?int $logisticId): ?string
    {
        if (!$logisticId) {
            return null;
        }

        return $this->logisticOptions()[$logisticId] ?? (string) $logisticId;
    }

    public function itemOptions(?string $search = null): array
    {
        $shop = $this->activeShop();

        if (!$shop) {
            return [];
        }

        $items = Cache::remember(
            'shopee:items:normal:' . $shop->id,
            now()->addMinutes(30),
            function () use ($shop) {
                $response = $this->client->getItemList($shop);
                $basicItems = collect(data_get($response, 'response.item', []));

                if ($basicItems->isEmpty()) {
                    return [];
                }

                $itemIds = $basicItems->pluck('item_id')->filter()->values()->all();
                $detailResponse = $this->client->getItemBaseInfo($shop, $itemIds);

                return collect(data_get($detailResponse, 'response.item_list', []))
                    ->mapWithKeys(function (array $item) {
                        $itemId = (int) data_get($item, 'item_id');
                        $name = data_get($item, 'item_name', 'Unnamed Item');
                        $sku = data_get($item, 'item_sku') ?: '-';
                        $stock = data_get($item, 'stock_info_v2.summary_info.total_available_stock', 0);

                        return [
                            $itemId => "{$name} | SKU: {$sku} | Stock: {$stock} | ID: {$itemId}",
                        ];
                    })
                    ->all();
            }
        );

        if (filled($search)) {
            $search = str($search)->lower()->toString();

            $items = collect($items)
                ->filter(fn(string $label) => str($label)->lower()->contains($search))
                ->take(50)
                ->all();
        }

        return collect($items)->take(50)->all();
    }

    public function itemLabel(?int $itemId): ?string
    {
        if (!$itemId) {
            return null;
        }

        return $this->itemOptions()[$itemId] ?? (string) $itemId;
    }

    public function modelOptions(?int $itemId): array
    {
        $shop = $this->activeShop();

        if (!$shop || !$itemId) {
            return [
                0 => 'Tanpa variasi atau belum memilih item',
            ];
        }

        return Cache::remember(
            'shopee:models:' . $shop->id . ':' . $itemId,
            now()->addMinutes(30),
            function () use ($shop, $itemId) {
                $response = $this->client->getModelList($shop, $itemId);

                $models = collect(data_get($response, 'response.model', []));

                if ($models->isEmpty()) {
                    return [
                        0 => 'Tanpa variasi',
                    ];
                }

                return $models
                    ->mapWithKeys(function (array $model) {
                        $modelId = (int) data_get($model, 'model_id');
                        $sku = data_get($model, 'model_sku') ?: '-';
                        $price = data_get($model, 'price_info.0.current_price', data_get($model, 'price_info.0.original_price', '-'));
                        $stock = data_get($model, 'stock_info_v2.summary_info.total_available_stock', '-');

                        return [
                            $modelId => "Model ID: {$modelId} | SKU: {$sku} | Stock: {$stock} | Price: {$price}",
                        ];
                    })
                    ->all();
            }
        );
    }

    public function modelLabel(?int $itemId, ?int $modelId): ?string
    {
        if ($modelId === null) {
            return null;
        }

        return $this->modelOptions($itemId)[$modelId] ?? (string) $modelId;
    }

    protected function loadLeafCategories(ShopeeShop $shop): array
    {
        $result = [];

        $walk = function (?int $parentId, string $prefix = '') use (&$walk, &$result, $shop) {
            $response = $this->client->getCategories($shop, $parentId);

            $categories = data_get($response, 'response.category_list', []);

            foreach ($categories as $category) {
                $id = (int) data_get($category, 'category_id');
                $name = data_get($category, 'display_category_name')
                    ?: data_get($category, 'category_name')
                    ?: ('Category #' . $id);

                $hasChildren = (bool) data_get($category, 'has_children', false);
                $label = trim($prefix . ' / ' . $name, ' /');

                if ($hasChildren) {
                    $walk($id, $label);
                } else {
                    $result[$id] = "{$label} #{$id}";
                }
            }
        };

        $walk(null);

        return $result;
    }
}
