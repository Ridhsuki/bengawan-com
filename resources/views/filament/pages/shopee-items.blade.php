<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <x-filament::section>
            <x-slot name="heading">
                Daftar Produk Shopee Sandbox
            </x-slot>
            <x-slot name="description">
                Gunakan halaman ini untuk melihat Item ID, SKU, stok, dan status produk dari toko Shopee yang sudah
                terhubung.
            </x-slot>
        </x-filament::section>

        @if (empty($items))
            <x-filament::section>
                <p style="font-size: 0.875rem; color: #6b7280;">
                    Belum ada produk Shopee yang ditemukan. Pastikan toko sudah authorize dan produk sudah dibuat di
                    Shopee Seller Sandbox.
                </p>
            </x-filament::section>
        @else
            <x-filament::section>
                <div class="fi-ta-content"
                    style="position: relative; overflow-x: auto; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
                    <table class="fi-ta-table"
                        style="width: 100%; table-layout: auto; text-align: left; border-collapse: collapse;">
                        <thead style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                            <tr>
                                <th class="fi-ta-header-cell" style="padding: 0.875rem 0.75rem; padding-left: 1.5rem;">
                                    Produk</th>
                                <th class="fi-ta-header-cell" style="padding: 0.875rem 0.75rem;">Item ID</th>
                                <th class="fi-ta-header-cell" style="padding: 0.875rem 0.75rem;">SKU</th>
                                <th class="fi-ta-header-cell" style="padding: 0.875rem 0.75rem;">Stok</th>
                                <th class="fi-ta-header-cell" style="padding: 0.875rem 0.75rem;">Harga</th>
                                <th class="fi-ta-header-cell" style="padding: 0.875rem 0.75rem;">Status</th>
                                <th class="fi-ta-header-cell" style="padding: 0.875rem 0.75rem; padding-right: 1.5rem;">
                                    Model</th>
                            </tr>
                        </thead>
                        <tbody style="white-space: nowrap;">
                            @foreach ($items as $item)
                                <tr class="fi-ta-row"
                                    style="background-color: #ffffff; border-bottom: 1px solid #e5e7eb;">
                                    <td class="fi-ta-cell" style="padding: 1rem 0.75rem; padding-left: 1.5rem;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            @if ($item['image'])
                                                <img src="{{ $item['image'] }}"
                                                    style="height: 2.5rem; width: 2.5rem; border-radius: 0.5rem; object-fit: cover;"
                                                    alt="">
                                            @endif
                                            <div>
                                                <div style="font-size: 0.875rem; font-weight: 500; color: #030712;">
                                                    {{ $item['item_name'] }}
                                                </div>
                                                <div style="font-size: 0.75rem; color: #6b7280;">
                                                    Category ID: {{ $item['category_id'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell"
                                        style="padding: 1rem 0.75rem; font-family: monospace; font-size: 0.875rem; color: #4b5563;">
                                        {{ $item['item_id'] }}
                                    </td>
                                    <td class="fi-ta-cell"
                                        style="padding: 1rem 0.75rem; font-size: 0.875rem; color: #4b5563;">
                                        {{ $item['item_sku'] }}
                                    </td>
                                    <td class="fi-ta-cell"
                                        style="padding: 1rem 0.75rem; font-size: 0.875rem; color: #4b5563;">
                                        {{ $item['stock'] }}
                                    </td>
                                    <td class="fi-ta-cell"
                                        style="padding: 1rem 0.75rem; font-size: 0.875rem; color: #4b5563;">
                                        Rp{{ number_format((float) $item['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="fi-ta-cell"
                                        style="padding: 1rem 0.75rem; font-size: 0.875rem; color: #4b5563;">
                                        {{ $item['status'] }}
                                    </td>
                                    <td class="fi-ta-cell"
                                        style="padding: 1rem 0.75rem; font-size: 0.875rem; color: #4b5563; padding-right: 1.5rem;">
                                        {{ $item['has_model'] ? 'Ada variasi' : 'Tanpa variasi' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
