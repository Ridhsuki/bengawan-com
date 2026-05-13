<?php

namespace App\Services\Shopee;

use App\Models\ShopeeShop;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

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

        if (! $shop) {
            return $this->filterOptions($this->fallbackCategories(), $search);
        }

        // Penting: UI hanya membaca cache. Jangan panggil rekursi API dari Livewire dropdown.
        $categories = Cache::get('shopee:categories:leaf:' . $shop->id, []);

        if (empty($categories)) {
            $categories = $this->fallbackCategories();
        }

        return $this->filterOptions($categories, $search);
    }

    public function categoryLabel(?int $categoryId): ?string
    {
        if (! $categoryId) {
            return null;
        }

        return $this->categoryOptions()[$categoryId] ?? (string) $categoryId;
    }

    public function brandOptions(?int $categoryId, ?string $search = null): array
    {
        $shop = $this->activeShop();

        if (! $shop || ! $categoryId) {
            return $this->filterOptions($this->fallbackBrands(), $search);
        }

        try {
            $brands = Cache::remember(
                'shopee:brands:' . $shop->id . ':' . $categoryId,
                now()->addHours(12),
                function () use ($shop, $categoryId) {
                    $response = $this->client->getBrandList($shop, $categoryId);

                    $items = collect(data_get($response, 'response.brand_list', []))
                        ->mapWithKeys(function (array $brand) {
                            $brandId = (int) data_get($brand, 'brand_id');

                            $name = data_get($brand, 'display_brand_name')
                                ?: data_get($brand, 'original_brand_name')
                                ?: data_get($brand, 'brand_name')
                                ?: ('Brand #' . $brandId);

                            return [$brandId => $name];
                        })
                        ->all();

                    return [0 => 'NoBrand'] + $items;
                }
            );
        } catch (Throwable $e) {
            Log::warning('Shopee brand options failed', [
                'category_id' => $categoryId,
                'error' => $e->getMessage(),
            ]);

            $brands = $this->fallbackBrands();
        }

        return $this->filterOptions($brands, $search);
    }

    public function brandLabel(?int $brandId, ?int $categoryId): ?string
    {
        if ($brandId === null) {
            return null;
        }

        return $this->brandOptions($categoryId)[$brandId] ?? (string) $brandId;
    }

    public function logisticOptions(?string $search = null): array
    {
        $shop = $this->activeShop();

        if (! $shop) {
            return $this->filterOptions($this->fallbackLogistics(), $search);
        }

        try {
            $logistics = Cache::remember(
                'shopee:logistics:' . $shop->id,
                now()->addHours(6),
                function () use ($shop) {
                    $response = $this->client->getLogistics($shop);

                    $items = collect(data_get($response, 'response.logistics_channel_list', []))
                        ->filter(fn (array $item) => (bool) data_get($item, 'enabled', true))
                        ->mapWithKeys(function (array $item) {
                            $id = (int) (
                                data_get($item, 'logistics_channel_id')
                                ?: data_get($item, 'logistic_id')
                            );

                            $name = data_get($item, 'logistics_channel_name')
                                ?: data_get($item, 'logistic_name')
                                ?: ('Logistic #' . $id);

                            return [$id => $name . ' #' . $id];
                        })
                        ->filter(fn ($label, $id) => $id > 0)
                        ->all();

                    return $items ?: $this->fallbackLogistics();
                }
            );
        } catch (Throwable $e) {
            Log::warning('Shopee logistic options failed', [
                'error' => $e->getMessage(),
            ]);

            $logistics = $this->fallbackLogistics();
        }

        return $this->filterOptions($logistics, $search);
    }

    public function logisticLabel(?int $logisticId): ?string
    {
        if (! $logisticId) {
            return null;
        }

        return $this->logisticOptions()[$logisticId] ?? (string) $logisticId;
    }

    public function itemOptions(?string $search = null): array
    {
        $shop = $this->activeShop();

        if (! $shop) {
            return [];
        }

        try {
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
        } catch (Throwable $e) {
            Log::warning('Shopee item options failed', [
                'error' => $e->getMessage(),
            ]);

            $items = [];
        }

        return $this->filterOptions($items, $search);
    }

    public function itemLabel(?int $itemId): ?string
    {
        if (! $itemId) {
            return null;
        }

        return $this->itemOptions()[$itemId] ?? (string) $itemId;
    }

    public function modelOptions(?int $itemId): array
    {
        $shop = $this->activeShop();

        if (! $shop || ! $itemId) {
            return [
                0 => 'Tanpa variasi atau belum memilih item',
            ];
        }

        try {
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
        } catch (Throwable $e) {
            Log::warning('Shopee model options failed', [
                'item_id' => $itemId,
                'error' => $e->getMessage(),
            ]);

            return [
                0 => 'Tanpa variasi',
            ];
        }
    }

    public function modelLabel(?int $itemId, ?int $modelId): ?string
    {
        if ($modelId === null) {
            return null;
        }

        return $this->modelOptions($itemId)[$modelId] ?? (string) $modelId;
    }

    public function warmCategories(ShopeeShop $shop): array
    {
        $categories = $this->loadLeafCategories($shop);

        if (empty($categories)) {
            $categories = $this->fallbackCategories();
        }

        Cache::put('shopee:categories:leaf:' . $shop->id, $categories, now()->addHours(12));

        return $categories;
    }

    protected function loadLeafCategories(ShopeeShop $shop): array
    {
        $result = [];

        $walk = function (?int $parentId, string $prefix = '', int $depth = 0) use (&$walk, &$result, $shop) {
            if ($depth > 6) {
                return;
            }

            try {
                $response = $this->client->getCategories($shop, $parentId);
            } catch (Throwable $e) {
                Log::warning('Shopee category load failed', [
                    'parent_id' => $parentId,
                    'error' => $e->getMessage(),
                ]);

                return;
            }

            $categories = data_get($response, 'response.category_list', []);

            foreach ($categories as $category) {
                $id = (int) data_get($category, 'category_id');

                if ($id <= 0) {
                    continue;
                }

                $name = data_get($category, 'display_category_name')
                    ?: data_get($category, 'category_name')
                    ?: ('Category #' . $id);

                $hasChildren = (bool) data_get($category, 'has_children', false);
                $label = trim($prefix . ' / ' . $name, ' /');

                if ($hasChildren) {
                    $walk($id, $label, $depth + 1);
                } else {
                    $result[$id] = "{$label} #{$id}";
                }
            }
        };

        $walk(null);

        return $result;
    }

    protected function filterOptions(array $options, ?string $search = null, int $limit = 50): array
    {
        $options = collect($options);

        if (filled($search)) {
            $search = str($search)->lower()->toString();

            $options = $options->filter(
                fn (string $label) => str($label)->lower()->contains($search)
            );
        }

        return $options->take($limit)->all();
    }

    protected function fallbackCategories(): array
    {
        return [
            300046 => 'Komputer & Aksesoris / Laptop #300046',
        ];
    }

    protected function fallbackBrands(): array
    {
        return [
            0 => 'NoBrand',
        ];
    }

    protected function fallbackLogistics(): array
    {
        return [
            81017 => "Sandbox-J&T Express #81017",
            81016 => "Sandbox-J&T Cargo #81016",
        ];
    }
}
