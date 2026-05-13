<x-filament-panels::page>
    <div class="space-y-6">
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
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Belum ada produk Shopee yang ditemukan. Pastikan toko sudah authorize dan produk sudah dibuat di
                    Shopee Seller Sandbox.
                </p>
            </x-filament::section>
        @else
            <x-filament::section>
                <div
                    class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:bg-white/5">
                    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    Produk</th>
                                <th class="fi-ta-header-cell px-3 py-3.5">Item ID</th>
                                <th class="fi-ta-header-cell px-3 py-3.5">SKU</th>
                                <th class="fi-ta-header-cell px-3 py-3.5">Stok</th>
                                <th class="fi-ta-header-cell px-3 py-3.5">Harga</th>
                                <th class="fi-ta-header-cell px-3 py-3.5">Status</th>
                                <th class="fi-ta-header-cell px-3 py-3.5 sm:last-of-type:pe-6">Model</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                            @foreach ($items as $item)
                                <tr class="fi-ta-row bg-white dark:bg-gray-900">
                                    <td class="fi-ta-cell px-3 py-4 sm:first-of-type:ps-6">
                                        <div class="flex items-center gap-3">
                                            @if ($item['image'])
                                                <img src="{{ $item['image'] }}"
                                                    class="h-10 w-10 rounded-lg object-cover" alt="">
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-gray-950 dark:text-white">
                                                    {{ $item['item_name'] }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    Category ID: {{ $item['category_id'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-ta-cell px-3 py-4 font-mono text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item['item_id'] }}</td>
                                    <td class="fi-ta-cell px-3 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item['item_sku'] }}</td>
                                    <td class="fi-ta-cell px-3 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item['stock'] }}</td>
                                    <td class="fi-ta-cell px-3 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        Rp{{ number_format((float) $item['price'], 0, ',', '.') }}</td>
                                    <td class="fi-ta-cell px-3 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item['status'] }}</td>
                                    <td
                                        class="fi-ta-cell px-3 py-4 text-sm text-gray-600 dark:text-gray-400 sm:last-of-type:pe-6">
                                        {{ $item['has_model'] ? 'Ada variasi' : 'Tanpa variasi' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
