<x-filament-panels::page>
    <style>
        .shp-wrapper{display:flex;flex-direction:column;gap:1.5rem}.shp-box{background-color:#fff;border:1px solid #e5e7eb;border-radius:.75rem;padding:1.5rem;box-shadow:0 1px 2px 0 rgb(0 0 0 / .05)}.shp-title{font-size:1rem;font-weight:600;color:#111827;margin:0}.shp-desc{font-size:.875rem;color:#6b7280;margin-top:.25rem}.shp-table-container{border:1px solid #e5e7eb;border-radius:.75rem;overflow-x:auto;background-color:#fff}.shp-table{width:100%;border-collapse:collapse;text-align:left;font-size:.875rem}.shp-table th{background-color:#f9fafb;padding:.75rem 1rem;font-weight:600;color:#111827;border-bottom:1px solid #e5e7eb}.shp-table td{padding:1rem;color:#4b5563;border-bottom:1px solid #e5e7eb;white-space:nowrap}.shp-table tbody tr:last-child td{border-bottom:none}.shp-desktop-view{display:block}.shp-mobile-view{display:none;flex-direction:column;gap:1rem}.shp-mobile-card{border:1px solid #e5e7eb;border-radius:.5rem;padding:1rem;background-color:#fff}.shp-mobile-row{display:flex;justify-content:space-between;font-size:.875rem;margin-bottom:.5rem;color:#4b5563}@media (max-width:768px){.shp-desktop-view{display:none}.shp-mobile-view{display:flex}}:is(.dark .shp-box),:is(.dark .shp-table-container),:is(.dark .shp-mobile-card){background-color:rgb(255 255 255 / .05);border-color:rgb(255 255 255 / .1)}:is(.dark .shp-title){color:#fff}:is(.dark .shp-desc),:is(.dark .shp-table td),:is(.dark .shp-mobile-row){color:#9ca3af}:is(.dark .shp-table th){background-color:rgb(255 255 255 / .05);color:#fff;border-color:rgb(255 255 255 / .1)}:is(.dark .shp-table td){border-color:rgb(255 255 255 / .05)}.shp-loading{opacity:.5;pointer-events:none;transition:opacity 0.2s}.shp-pagination-wrapper{margin-top:1.5rem;padding-top:1rem;border-top:1px solid #e5e7eb}.shp-pagination-wrapper nav{display:flex;align-items:center;justify-content:space-between;width:100%}.shp-pagination-wrapper nav>div:nth-child(1){display:flex;justify-content:space-between;flex:1;gap:.5rem}.shp-pagination-wrapper nav>div:nth-child(2){display:none}@media (min-width:640px){.shp-pagination-wrapper nav>div:nth-child(1){display:none}.shp-pagination-wrapper nav>div:nth-child(2){display:flex;flex:1;align-items:center;justify-content:space-between}}.shp-pagination-wrapper nav p{font-size:.875rem;color:#6b7280;margin:0}.shp-pagination-wrapper nav p span{font-weight:600;color:#111827}.shp-pagination-wrapper button,.shp-pagination-wrapper a,.shp-pagination-wrapper span[aria-disabled]{position:relative;display:inline-flex;align-items:center;padding:.5rem 1rem;font-size:.875rem;font-weight:500;color:#4b5563;background-color:#fff;border:1px solid #e5e7eb;text-decoration:none;cursor:pointer;transition:background-color 0.2s}.shp-pagination-wrapper button:hover,.shp-pagination-wrapper a:hover{background-color:#f9fafb;color:#111827;z-index:2}.shp-pagination-wrapper button[disabled],.shp-pagination-wrapper span[aria-disabled]{color:#9ca3af;cursor:not-allowed;background-color:#f9fafb}.shp-pagination-wrapper svg{width:1.25rem;height:1.25rem}.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child>span{display:inline-flex;box-shadow:0 1px 2px 0 rgb(0 0 0 / .05);border-radius:.375rem}.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child button,.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child a,.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child span[aria-disabled],.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child span[aria-current]>span{margin-left:-1px}.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child>span>span:first-child button,.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child>span>span:first-child span{border-top-left-radius:.375rem;border-bottom-left-radius:.375rem}.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child>span>span:last-child button,.shp-pagination-wrapper nav>div:nth-child(2)>div:last-child>span>span:last-child span{border-top-right-radius:.375rem;border-bottom-right-radius:.375rem}.shp-pagination-wrapper span[aria-current="page"]>span{position:relative;display:inline-flex;align-items:center;padding:.5rem 1rem;font-size:.875rem;font-weight:600;color:#172D9D;background-color:#eff6ff;border:1px solid #e5e7eb;z-index:10;margin-left:-1px}:is(.dark .shp-pagination-wrapper){border-color:rgb(255 255 255 / .1)}:is(.dark .shp-pagination-wrapper nav p){color:#9ca3af}:is(.dark .shp-pagination-wrapper nav p span){color:#fff}:is(.dark .shp-pagination-wrapper button),:is(.dark .shp-pagination-wrapper a),:is(.dark .shp-pagination-wrapper span[aria-disabled]){background-color:rgb(255 255 255 / .05);border-color:rgb(255 255 255 / .1);color:#9ca3af}:is(.dark .shp-pagination-wrapper button:hover),:is(.dark .shp-pagination-wrapper a:hover){background-color:rgb(255 255 255 / .1);color:#fff}:is(.dark .shp-pagination-wrapper button[disabled]),:is(.dark .shp-pagination-wrapper span[aria-disabled]){color:#4b5563;background-color:#fff0}:is(.dark .shp-pagination-wrapper span[aria-current="page"]>span){background-color:rgb(255 255 255 / .15);color:#fff;border-color:rgb(255 255 255 / .1)}.shp-truncate{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;text-overflow:ellipsis;white-space:normal;max-width:300px}@media (max-width:768px){.shp-truncate{max-width:100%}}
    </style>

    <div class="shp-wrapper">
        <div class="shp-box">
            <h2 class="shp-title">Daftar Produk Shopee Sandbox</h2>
            <p class="shp-desc">Halaman ini di-cache selama 10 menit untuk performa. Klik tombol "Refresh Data API" di
                atas untuk mengambil data terbaru.</p>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 250px;">
                <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
                    <x-filament::input type="search" wire:model.live.debounce.500ms="search"
                        placeholder="Cari nama produk, SKU, atau Item ID..." />
                </x-filament::input.wrapper>
            </div>

            <div style="width: 200px;">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="statusFilter">
                        <option value="all">Semua Status</option>
                        <option value="NORMAL">Normal</option>
                        <option value="BANNED">Banned</option>
                        <option value="DELETED">Deleted</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            <div wire:loading wire:target="search, statusFilter">
                <x-filament::loading-indicator class="h-5 w-5" />
            </div>
        </div>

        <div wire:loading.class="shp-loading" wire:target="search, statusFilter, gotoPage, nextPage, previousPage">
            @if ($this->getPaginatedItems->isEmpty())
                <div class="shp-box" style="text-align: center; padding: 3rem 1rem;">
                    <h3 class="shp-title">Tidak ada data ditemukan</h3>
                    <p class="shp-desc">Coba sesuaikan kata kunci pencarian atau filter status Anda.</p>
                </div>
            @else
                <div class="shp-table-container shp-desktop-view">
                    <table class="shp-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Item ID</th>
                                <th>SKU</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->getPaginatedItems as $item)
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            @if ($item['image'])
                                                <img src="{{ $item['image'] }}"
                                                    style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; object-fit: cover;"
                                                    alt="Product Image" loading="lazy">
                                            @endif
                                            <div>
                                                <div class="shp-title shp-truncate" style="font-size: 0.875rem;"
                                                    x-tooltip="'{{ addslashes($item['item_name']) }}'">
                                                    {{ $item['item_name'] }}
                                                </div>
                                                <div class="shp-desc" style="font-size: 0.75rem;">Kategori:
                                                    {{ $item['category_id'] }} |
                                                    {{ $item['has_model'] ? 'Varian' : 'Tunggal' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-family: monospace;">{{ $item['item_id'] }}</td>
                                    <td>{{ $item['item_sku'] }}</td>
                                    <td>{{ $item['stock'] }}</td>
                                    <td>Rp{{ number_format((float) $item['price'], 0, ',', '.') }}</td>
                                    <td>
                                        <x-filament::badge
                                            color="{{ $item['status'] === 'NORMAL' ? 'success' : 'danger' }}">
                                            {{ $item['status'] }}
                                        </x-filament::badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="shp-mobile-view">
                    @foreach ($this->getPaginatedItems as $item)
                        <div class="shp-mobile-card">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                @if ($item['image'])
                                    <img src="{{ $item['image'] }}"
                                        style="width: 3rem; height: 3rem; border-radius: 0.5rem; object-fit: cover;"
                                        alt="Product Image" loading="lazy">
                                @endif
                                <div>
                                    <div class="shp-title shp-truncate" style="font-size: 0.875rem;"
                                        title="{{ $item['item_name'] }}">
                                        {{ $item['item_name'] }}
                                    </div>
                                    <div class="shp-desc" style="font-size: 0.75rem;">ID: <span
                                            style="font-family: monospace;">{{ $item['item_id'] }}</span></div>
                                </div>
                            </div>
                            <div class="shp-mobile-row"><strong>SKU</strong> <span>{{ $item['item_sku'] }}</span></div>
                            <div class="shp-mobile-row"><strong>Stok</strong> <span>{{ $item['stock'] }}</span></div>
                            <div class="shp-mobile-row"><strong>Harga</strong>
                                <span>Rp{{ number_format((float) $item['price'], 0, ',', '.') }}</span>
                            </div>
                            <div class="shp-mobile-row" style="margin-bottom: 0;"><strong>Status</strong>
                                <x-filament::badge
                                    color="{{ $item['status'] === 'NORMAL' ? 'success' : 'danger' }}">{{ $item['status'] }}</x-filament::badge>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="shp-pagination-wrapper">
                    {{ $this->getPaginatedItems->links() }}
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
