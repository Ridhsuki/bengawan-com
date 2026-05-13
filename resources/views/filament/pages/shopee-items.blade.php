<x-filament-panels::page>
    <style>
        .shp-wrapper{display:flex;flex-direction:column;gap:1.5rem}.shp-box{background-color:#fff;border:1px solid #e5e7eb;border-radius:.75rem;padding:1.5rem;box-shadow:0 1px 2px 0 rgb(0 0 0 / .05)}.shp-title{font-size:1rem;font-weight:600;color:#111827;margin:0}.shp-desc{font-size:.875rem;color:#6b7280;margin-top:.25rem}.shp-table-container{border:1px solid #e5e7eb;border-radius:.75rem;overflow-x:auto;background-color:#fff}.shp-table{width:100%;border-collapse:collapse;text-align:left;font-size:.875rem}.shp-table th{background-color:#f9fafb;padding:.75rem 1rem;font-weight:600;color:#111827;border-bottom:1px solid #e5e7eb}.shp-table td{padding:1rem;color:#4b5563;border-bottom:1px solid #e5e7eb;white-space:nowrap}.shp-table tbody tr:last-child td{border-bottom:none}.shp-desktop-view{display:block}.shp-mobile-view{display:none;flex-direction:column;gap:1rem}.shp-mobile-card{border:1px solid #e5e7eb;border-radius:.5rem;padding:1rem;background-color:#fff}.shp-mobile-row{display:flex;justify-content:space-between;font-size:.875rem;margin-bottom:.5rem;color:#4b5563}@media (max-width:768px){.shp-desktop-view{display:none}.shp-mobile-view{display:flex}}:is(.dark .shp-box),:is(.dark .shp-table-container),:is(.dark .shp-mobile-card){background-color:rgb(255 255 255 / .05);border-color:rgb(255 255 255 / .1)}:is(.dark .shp-title){color:#fff}:is(.dark .shp-desc),:is(.dark .shp-table td),:is(.dark .shp-mobile-row){color:#9ca3af}:is(.dark .shp-table th){background-color:rgb(255 255 255 / .05);color:#fff;border-color:rgb(255 255 255 / .1)}:is(.dark .shp-table td){border-color:rgb(255 255 255 / .05)}
    </style>

    <div class="shp-wrapper">
        <div class="shp-box">
            <h2 class="shp-title">Daftar Produk Shopee Sandbox</h2>
            <p class="shp-desc">Gunakan halaman ini untuk melihat Item ID, SKU, stok, dan status produk dari toko Shopee
                yang sudah terhubung.</p>
        </div>

        @if (empty($items))
            <div class="shp-box">
                <p class="shp-desc">Belum ada produk Shopee yang ditemukan. Pastikan toko sudah authorize dan produk
                    sudah dibuat di Shopee Seller Sandbox.</p>
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
                            <th>Model</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        @if ($item['image'])
                                            <img src="{{ $item['image'] }}"
                                                style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; object-fit: cover;"
                                                alt="">
                                        @endif
                                        <div>
                                            <div class="shp-title" style="font-size: 0.875rem;">{{ $item['item_name'] }}
                                            </div>
                                            <div class="shp-desc" style="font-size: 0.75rem;">Category ID:
                                                {{ $item['category_id'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-family: monospace;">{{ $item['item_id'] }}</td>
                                <td>{{ $item['item_sku'] }}</td>
                                <td>{{ $item['stock'] }}</td>
                                <td>Rp{{ number_format((float) $item['price'], 0, ',', '.') }}</td>
                                <td>{{ $item['status'] }}</td>
                                <td>{{ $item['has_model'] ? 'Ada variasi' : 'Tanpa variasi' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="shp-mobile-view">
                @foreach ($items as $item)
                    <div class="shp-mobile-card">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                            @if ($item['image'])
                                <img src="{{ $item['image'] }}"
                                    style="width: 3rem; height: 3rem; border-radius: 0.5rem; object-fit: cover;"
                                    alt="">
                            @endif
                            <div>
                                <div class="shp-title" style="font-size: 0.875rem;">{{ $item['item_name'] }}</div>
                                <div class="shp-desc" style="font-size: 0.75rem;">Item ID: <span
                                        style="font-family: monospace;">{{ $item['item_id'] }}</span></div>
                            </div>
                        </div>

                        <div class="shp-mobile-row"><strong>SKU</strong> <span>{{ $item['item_sku'] }}</span></div>
                        <div class="shp-mobile-row"><strong>Stok</strong> <span>{{ $item['stock'] }}</span></div>
                        <div class="shp-mobile-row"><strong>Harga</strong>
                            <span>Rp{{ number_format((float) $item['price'], 0, ',', '.') }}</span></div>
                        <div class="shp-mobile-row"><strong>Status</strong> <span>{{ $item['status'] }}</span></div>
                        <div class="shp-mobile-row" style="margin-bottom: 0;"><strong>Model</strong>
                            <span>{{ $item['has_model'] ? 'Ada variasi' : 'Tanpa variasi' }}</span></div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>
