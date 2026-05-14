<?php

namespace App\Filament\Pages;

use App\Models\Product;
use App\Models\ShopeeShop;
use App\Services\Shopee\ShopeeClient;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use BackedEnum;

class ShopeeItems extends Page
{
    use WithPagination;

    protected static ?string $navigationLabel = 'Shopee Items';

    protected static ?string $title = 'Shopee Items';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-bag';

    protected static string|\UnitEnum|null $navigationGroup = 'Marketplace';

    protected string $view = 'filament.pages.shopee-items';

    // State untuk Search dan Filter
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    public ?int $selectedShopId = null;

    public function mount(): void
    {
        $this->selectedShopId = ShopeeShop::where('is_active', true)->value('id');
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function getAllItems()
    {
        $shop = ShopeeShop::find($this->selectedShopId);

        if (!$shop) {
            return collect([]);
        }

        $allItems = Cache::remember('shopee:full_items:' . $shop->id, now()->addMinutes(10), function () use ($shop) {
            $client = app(ShopeeClient::class);
            $response = $client->getItemList($shop);
            $basicItems = collect(data_get($response, 'response.item', []));

            if ($basicItems->isEmpty()) {
                return [];
            }

            $itemIds = $basicItems->pluck('item_id')->filter()->values()->all();
            $detailResponse = $client->getItemBaseInfo($shop, $itemIds);

            return collect(data_get($detailResponse, 'response.item_list', []))
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
                ->all();
        });

        $collection = collect($allItems);

        if (!empty($this->search)) {
            $search = strtolower($this->search);
            $collection = $collection->filter(function ($item) use ($search) {
                return str_contains(strtolower($item['item_name']), $search)
                    || str_contains(strtolower($item['item_sku']), $search)
                    || (string) $item['item_id'] === $search;
            });
        }

        if ($this->statusFilter !== 'all') {
            $collection = $collection->filter(function ($item) {
                return strtolower($item['status']) === strtolower($this->statusFilter);
            });
        }

        return $collection->values();
    }

    #[Computed]
    public function getPaginatedItems()
    {
        $items = $this->getAllItems;
        $perPage = 10;
        $page = $this->getPage();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Tarik Ulang API Shopee')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function () {
                    Cache::forget('shopee:full_items:' . $this->selectedShopId);
                    $this->resetPage();

                    Notification::make()
                        ->title('Daftar produk Shopee berhasil diperbarui dari server.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function linkToProduct(int $productId, int $itemId): void
    {
        $item = $this->getAllItems->firstWhere('item_id', $itemId);

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
