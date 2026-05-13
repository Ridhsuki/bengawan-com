<x-filament-panels::page>
    <style>
        .shp-wrapper{display:flex;flex-direction:column;gap:1.5rem}.shp-box{background-color:#fff;border:1px solid #e5e7eb;border-radius:.75rem;padding:1.5rem;box-shadow:0 1px 2px 0 rgb(0 0 0 / .05)}.shp-title{font-size:1rem;font-weight:600;color:#111827;margin:0}.shp-desc{font-size:.875rem;color:#6b7280;margin-top:.25rem}.shp-table-container{border:1px solid #e5e7eb;border-radius:.75rem;overflow-x:auto;background-color:#fff}.shp-table{width:100%;border-collapse:collapse;text-align:left;font-size:.875rem}.shp-table th{background-color:#f9fafb;padding:.75rem 1rem;font-weight:600;color:#111827;border-bottom:1px solid #e5e7eb}.shp-table td{padding:1rem;color:#4b5563;border-bottom:1px solid #e5e7eb;white-space:nowrap}.shp-table tbody tr:last-child td{border-bottom:none}.shp-desktop-view{display:block}.shp-mobile-view{display:none;flex-direction:column;gap:1rem}.shp-mobile-card{border:1px solid #e5e7eb;border-radius:.5rem;padding:1rem;background-color:#fff}.shp-mobile-row{display:flex;justify-content:space-between;font-size:.875rem;margin-bottom:.5rem;color:#4b5563}@media (max-width:768px){.shp-desktop-view{display:none}.shp-mobile-view{display:flex}}:is(.dark .shp-box),:is(.dark .shp-table-container),:is(.dark .shp-mobile-card){background-color:rgb(255 255 255 / .05);border-color:rgb(255 255 255 / .1)}:is(.dark .shp-title){color:#fff}:is(.dark .shp-desc),:is(.dark .shp-table td),:is(.dark .shp-mobile-row){color:#9ca3af}:is(.dark .shp-table th){background-color:rgb(255 255 255 / .05);color:#fff;border-color:rgb(255 255 255 / .1)}:is(.dark .shp-table td){border-color:rgb(255 255 255 / .05)}.shp-loading{opacity:.5;pointer-events:none;transition:opacity 0.2s}
    </style>

    <div class="shp-wrapper">
        <div class="shp-box">
            <h2 class="shp-title">Daftar Produk Shopee Sandbox</h2>
            <p class="shp-desc">Halaman ini di-cache selama 10 menit untuk performa. Klik tombol "Refresh Data API" di
                atas untuk mengambil data terbaru.</p>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 250px;">
                {{-- Gunakan UI native Filament. wire:model.live.debounce.500ms mengurangi beban server saat mengetik --}}
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

            {{-- Loading spinner otomatis saat searching --}}
            <div wire:loading wire:target="search, statusFilter">
                <x-filament::loading-indicator class="h-5 w-5" />
            </div>
        </div>

        {{-- Bungkus data tabel dengan wire:loading.class agar terlihat redup saat loading --}}
        <div wire:loading.class="shp-loading" wire:target="search, statusFilter, gotoPage, nextPage, previousPage">
            @if ($this->getPaginatedItems->isEmpty())
                <div class="shp-box" style="text-align: center; padding: 3rem 1rem;">
                    {{-- <x-filament::icon icon="heroicon-o-x-circle" class="h-12 w-12 mx-auto text-gray-400 mb-2" /> --}}
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
                                                    alt="">
                                            @endif
                                            <div>
                                                <div class="shp-title" style="font-size: 0.875rem;">
                                                    {{ $item['item_name'] }}</div>
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
                                        alt="">
                                @endif
                                <div>
                                    <div class="shp-title" style="font-size: 0.875rem;">{{ $item['item_name'] }}</div>
                                    <div class="shp-desc" style="font-size: 0.75rem;">ID: <span
                                            style="font-family: monospace;">{{ $item['item_id'] }}</span></div>
                                </div>
                            </div>
                            <div class="shp-mobile-row"><strong>SKU</strong> <span>{{ $item['item_sku'] }}</span></div>
                            <div class="shp-mobile-row"><strong>Stok</strong> <span>{{ $item['stock'] }}</span></div>
                            <div class="shp-mobile-row"><strong>Harga</strong>
                                <span>Rp{{ number_format((float) $item['price'], 0, ',', '.') }}</span></div>
                            <div class="shp-mobile-row" style="margin-bottom: 0;"><strong>Status</strong>
                                <x-filament::badge
                                    color="{{ $item['status'] === 'NORMAL' ? 'success' : 'danger' }}">{{ $item['status'] }}</x-filament::badge>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 1.5rem;">
                    {{ $this->getPaginatedItems->links('filament::components.pagination') }}
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
