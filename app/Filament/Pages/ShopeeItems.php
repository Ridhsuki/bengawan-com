<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use BackedEnum;

class ShopeeItems extends Page
{
    protected static ?string $navigationLabel = 'Shopee Items';

    protected static ?string $title = 'Shopee Items';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string|\UnitEnum|null $navigationGroup = 'Marketplace';

    protected string $view = 'filament.pages.shopee-items';

    public array $items = [];

    public ?int $selectedShopId = null;

    public function mount(ShopeeClient $client): void
    {
        $this->selectedShopId = ShopeeShop::where('is_active', true)->value('id');
        $this->loadItems($client);
    }

    public function loadItems(ShopeeClient $client): void
    {
        $shop = ShopeeShop::find($this->selectedShopId);

        if (!$shop) {
            $this->items = [];
            return;
        }

        $response = $client->getItemList($shop);
        $basicItems = collect(data_get($response, 'response.item', []));

        if ($basicItems->isEmpty()) {
            $this->items = [];
            return;
        }

        $itemIds = $basicItems->pluck('item_id')->filter()->values()->all();

        $detailResponse = $client->getItemBaseInfo($shop, $itemIds);

        $this->items = collect(data_get($detailResponse, 'response.item_list', []))
            ->map(function (array $item) use ($shop) {
                return [
                    'shop_local_id' => $shop->id,
                    'shop_id' => $shop->shop_id,
                    'item_id' => data_get($item, 'item_id'),
                    'item_name' => data_get($item, 'item_name'),
                    'item_sku' => data_get($item, 'item_sku') ?: '-',
                    'status' => data_get($item, 'item_status'),
                    'has_model' => (bool) data_get($item, 'has_model'),
                    'stock' => data_get($item, 'stock_info_v2.summary_info.total_available_stock', 0),
                    'price' => data_get($item, 'price_info.0.current_price'),
                    'image' => data_get($item, 'image.image_url_list.0'),
                    'category_id' => data_get($item, 'category_id'),
                ];
            })
            ->values()
            ->all();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Shopee Items')
                ->icon('heroicon-o-arrow-path')
                ->action(function (ShopeeClient $client) {
                    $this->loadItems($client);

                    Notification::make()
                        ->title('Daftar produk Shopee diperbarui.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function linkToProduct(int $productId, int $itemId): void
    {
        $item = collect($this->items)->firstWhere('item_id', $itemId);

        if (!$item) {
            Notification::make()
                ->title('Item Shopee tidak ditemukan pada halaman.')
                ->danger()
                ->send();

            return;
        }

        Product::whereKey($productId)->update([
            'shopee_shop_id' => $item['shop_local_id'],
            'shopee_item_id' => $item['item_id'],
            'shopee_model_id' => 0,
            'shopee_sku' => $item['item_sku'] !== '-' ? $item['item_sku'] : null,
            'sync_shopee_stock' => true,
            'shopee_category_id' => $item['category_id'],
        ]);

        Notification::make()
            ->title('Produk Bengawan berhasil dihubungkan ke item Shopee.')
            ->success()
            ->send();
    }
}
