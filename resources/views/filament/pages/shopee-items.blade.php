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
                <div class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                    <div
                        class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/20">
                        <x-filament::icon icon="heroicon-o-shopping-bag"
                            class="h-12 w-12 text-blue-600 dark:text-blue-400" />
                    </div>
                    <h3 class="mt-6 text-lg font-medium text-gray-900 dark:text-white">Belum ada produk Shopee</h3>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-md text-center">
                        Pastikan toko sudah authorize dan produk sudah dibuat di Shopee Seller Sandbox.
                    </p>
                </div>
            </x-filament::section>
        @else
            <x-filament::section>
                <x-slot name="heading">
                    {{ count($items) }} {{ Str::plural('Produk', count($items)) }} Ditemukan
                </x-slot>

                <div class="overflow-x-auto">
                    <div class="fi-ta-content relative divide-y divide-gray-200/50 dark:divide-white/10">
                        <table
                            class="fi-ta-table w-full table-auto divide-y divide-gray-200/50 text-sm dark:divide-white/10">
                            {{-- Mobile Header (Hidden on desktop) --}}
                            <thead class="sm:hidden">
                                <tr>
                                    <th
                                        class="fi-ta-header-cell py-4 pr-6 text-left font-medium text-gray-900 dark:text-white">
                                        Semua Produk
                                    </th>
                                </tr>
                            </thead>

                            <thead
                                class="bg-gray-50/50 dark:bg-white/5 sticky top-0 z-10 border-b border-gray-200/50 dark:border-white/10">
                                <tr>
                                    <th
                                        class="fi-ta-header-cell px-4 py-4 text-left font-medium text-gray-900 dark:text-white [&:first-child]:ps-6 [&:last-child]:pe-6 sm:w-48">
                                        Produk
                                    </th>
                                    <th
                                        class="fi-ta-header-cell px-4 py-4 text-left font-medium text-gray-900 dark:text-white sm:w-32">
                                        Item ID
                                    </th>
                                    <th
                                        class="fi-ta-header-cell px-4 py-4 text-left font-medium text-gray-900 dark:text-white sm:w-32">
                                        SKU
                                    </th>
                                    <th
                                        class="fi-ta-header-cell px-4 py-4 text-right font-medium text-gray-900 dark:text-white sm:w-20">
                                        Stok
                                    </th>
                                    <th
                                        class="fi-ta-header-cell px-4 py-4 text-right font-medium text-gray-900 dark:text-white sm:w-24">
                                        Harga
                                    </th>
                                    <th
                                        class="fi-ta-header-cell px-4 py-4 text-left font-medium text-gray-900 dark:text-white sm:w-24">
                                        Status
                                    </th>
                                    <th
                                        class="fi-ta-header-cell px-4 py-4 text-left font-medium text-gray-900 dark:text-white [&:last-child]:pe-6 sm:w-28">
                                        Model
                                    </th>
                                </tr>
                            </thead>

                            <tbody
                                class="divide-y divide-gray-200/50 bg-white/50 dark:bg-gray-900/50 dark:divide-white/10">
                                @foreach ($items as $item)
                                    <tr
                                        class="hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors duration-150 group/filament">
                                        <td
                                            class="fi-ta-cell px-4 py-4 [&:first-child]:ps-6 [&:last-child]:pe-6 sm:w-48">
                                            <div class="flex items-start gap-3 sm:max-w-[200px]">
                                                @if ($item['image'])
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ $item['image'] }}"
                                                            class="h-12 w-12 rounded-lg object-cover ring-1 ring-gray-200/50 dark:ring-white/10 shadow-sm hover:shadow-md transition-shadow duration-200 group-hover/filament:shadow-md"
                                                            alt="{{ $item['item_name'] }}" loading="lazy" width="48"
                                                            height="48" />
                                                    </div>
                                                @endif
                                                <div class="min-w-0 flex-1">
                                                    <div
                                                        class="truncate text-sm font-medium text-gray-900 dark:text-white leading-tight">
                                                        {{ Str::limit($item['item_name'], 40, '...') }}
                                                    </div>
                                                    <div
                                                        class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                        <span class="truncate">Cat: {{ $item['category_id'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td
                                            class="fi-ta-cell px-4 py-4 hidden sm:table-cell font-mono text-xs text-gray-600 dark:text-gray-400 truncate max-w-[120px]">
                                            {{ Str::limit($item['item_id'], 20, '...') }}
                                        </td>

                                        <td
                                            class="fi-ta-cell px-4 py-4 hidden sm:table-cell text-xs text-gray-600 dark:text-gray-400 truncate max-w-[120px]">
                                            {{ Str::limit($item['item_sku'], 20, '...') }}
                                        </td>

                                        <td
                                            class="fi-ta-cell px-4 py-4 hidden sm:table-cell text-xs font-medium text-right text-gray-900 dark:text-white">
                                            {{ number_format($item['stock']) }}
                                        </td>

                                        <td
                                            class="fi-ta-cell px-4 py-4 hidden sm:table-cell text-xs font-medium text-right text-gray-900 dark:text-white">
                                            Rp{{ number_format((float) $item['price'], 0, ',', '.') }}
                                        </td>

                                        <td class="fi-ta-cell px-4 py-4 hidden sm:table-cell text-xs">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                {{ $item['status'] ?? 'Normal' }}
                                            </span>
                                        </td>

                                        <td
                                            class="fi-ta-cell px-4 py-4 [&:last-child]:pe-6 hidden sm:table-cell text-xs text-gray-600 dark:text-gray-400">
                                            <span class="inline-flex items-center gap-1">
                                                <x-filament::icon
                                                    icon="{{ $item['has_model'] ? 'heroicon-o-cube' : 'heroicon-o-cube-transparent' }}"
                                                    class="h-3 w-3 {{ $item['has_model'] ? 'text-blue-500' : 'text-gray-400' }}" />
                                                {{ $item['has_model'] ? 'Ada variasi' : 'Tanpa variasi' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="sm:hidden space-y-4 mt-4">
                    @foreach ($items as $item)
                        <div
                            class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200/50 dark:border-white/10 p-6 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-200 group">
                            <div class="flex items-start gap-4">
                                <div class="flex flex-col">
                                    @if ($item['image'])
                                        <img src="{{ $item['image'] }}"
                                            class="h-20 w-20 rounded-xl object-cover shadow-sm ring-1 ring-gray-200/50 dark:ring-white/10 mb-3"
                                            alt="{{ $item['item_name'] }}" loading="lazy" width="80"
                                            height="80" />
                                    @endif
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Cat:
                                        {{ $item['category_id'] }}</div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="font-medium text-gray-900 dark:text-white text-base mb-2 truncate leading-tight">
                                        {{ Str::limit($item['item_name'], 50, '...') }}
                                    </h3>

                                    <div class="grid grid-cols-2 gap-3 mb-3">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Item ID</div>
                                            <div
                                                class="font-mono text-sm text-gray-900 dark:text-white truncate max-w-[140px]">
                                                {{ Str::limit($item['item_id'], 25, '...') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">SKU</div>
                                            <div class="text-sm text-gray-900 dark:text-white truncate">
                                                {{ Str::limit($item['item_sku'], 25, '...') }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Stok</div>
                                            <div class="font-medium text-sm text-gray-900 dark:text-white">
                                                {{ number_format($item['stock']) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Harga</div>
                                            <div class="font-medium text-sm text-gray-900 dark:text-white">
                                                Rp{{ number_format((float) $item['price'], 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="flex items-center gap-3 pt-3 border-t border-gray-200/50 dark:border-white/10">
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 text-xs font-medium rounded-full">
                                            {{ $item['status'] ?? 'Normal' }}
                                        </span>
                                        <span class="text-xs text-gray-600 dark:text-gray-400 flex items-center gap-1">
                                            <x-filament::icon
                                                icon="{{ $item['has_model'] ? 'heroicon-o-cube' : 'heroicon-o-cube-transparent' }}"
                                                class="h-3 w-3 {{ $item['has_model'] ? 'text-blue-500' : 'text-gray-400' }}" />
                                            {{ $item['has_model'] ? 'Ada variasi' : 'Tanpa variasi' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
