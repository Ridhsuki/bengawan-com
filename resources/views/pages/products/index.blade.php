<x-app-layout>
    @push('styles')
        <style>
            .accordion-content {
                max-height: 1000px;
                overflow: hidden;
                transition: max-height 0.3s ease-in-out;
            }

            .accordion-content.collapsed {
                max-height: 0;
            }

            .rotate-icon {
                transform: rotate(180deg);
                transition: transform 0.3s ease-in-out;
            }
        </style>
    @endpush
    <div class="container mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8 items-start">
        <button id="mobileFilterBtn"
            class="lg:hidden w-full bg-white border border-gray-300 py-2 rounded-lg font-bold text-gray-700 shadow-sm mb-4">
            <i class="fa-solid fa-filter mr-2"></i> Filter Produk
        </button>

        <aside id="sidebarFilter" class="w-full lg:w-1/4 flex-col gap-6 hidden lg:flex">

            <div class="bg-white rounded-3xl shadow-lg p-6">
                <button
                    class="accordion-header w-full flex justify-between items-center text-gray-600 font-bold text-lg mb-4 pb-2 border-b-2 border-gray-100">
                    <span>Katalog</span>
                    <i class="fa-solid fa-chevron-down transition-transform duration-300"></i>
                </button>
                <div class="accordion-content">
                    <ul class="space-y-3 text-gray-500 font-medium">
                        <li>
                            <a href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => null]) }}"
                                class="{{ request('category') === null ? 'text-brand-blue font-bold' : 'hover:text-brand-blue transition' }}">
                                Semua Kategori
                            </a>
                        </li>

                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['category' => $category->slug, 'page' => null]) }}"
                                    class="{{ request('category') == $category->slug ? 'text-brand-blue font-bold' : 'hover:text-brand-blue transition' }}">
                                    {{ $category->name }}
                                    <span class="text-xs text-gray-400">({{ $category->products_count }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg p-6">
                <button
                    class="accordion-header w-full flex justify-between items-center text-gray-600 font-bold text-lg mb-4 pb-2 border-b-2 border-gray-100">
                    <span>Range Harga</span>
                    <i class="fa-solid fa-chevron-down transition-transform duration-300"></i>
                </button>
                <div class="accordion-content">
                    <div class="flex flex-col gap-3">
                        @php
                            $currentPrice = request('price');
                            $baseClass =
                                'border rounded-full py-1.5 px-4 text-sm font-medium transition text-center block w-full';
                            $inactiveClass =
                                'border-gray-400 text-gray-500 hover:bg-brand-blue hover:text-white hover:border-brand-blue';
                            $activeClass = 'bg-brand-blue text-white border-brand-blue';
                        @endphp

                        <a href="{{ request()->fullUrlWithQuery(['price' => null, 'page' => null]) }}"
                            class="{{ $baseClass }} {{ $currentPrice === null ? $activeClass : $inactiveClass }}">
                            Semua Harga
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['price' => 'lt_2m', 'page' => null]) }}"
                            class="{{ $baseClass }} {{ $currentPrice == 'lt_2m' ? $activeClass : $inactiveClass }}">
                            &lt; Rp 2.000.000
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['price' => 'lt_5m', 'page' => null]) }}"
                            class="{{ $baseClass }} {{ $currentPrice == 'lt_5m' ? $activeClass : $inactiveClass }}">
                            &lt; Rp 5.000.000
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['price' => 'lt_10m', 'page' => null]) }}"
                            class="{{ $baseClass }} {{ $currentPrice == 'lt_10m' ? $activeClass : $inactiveClass }}">
                            &lt; Rp 10.000.000
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['price' => 'lt_20m', 'page' => null]) }}"
                            class="{{ $baseClass }} {{ $currentPrice == 'lt_20m' ? $activeClass : $inactiveClass }}">
                            &lt; Rp 20.000.000
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['price' => 'gt_20m', 'page' => null]) }}"
                            class="{{ $baseClass }} {{ $currentPrice == 'gt_20m' ? $activeClass : $inactiveClass }}">
                            &gt; Rp 20.000.000
                        </a>
                    </div>
                </div>
            </div>

        </aside>

        <main class="w-full lg:w-3/4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($products as $product)
                    <div
                        class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                        <div class="h-48 skeleton w-full relative overflow-hidden bg-gray-200">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/no-image.webp') }}"
                                alt="{{ $product->name }}" loading="lazy" decoding="async"
                                class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700 opacity-0"
                                onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('skeleton');"
                                onerror="this.onerror=null;this.src='{{ asset('assets/img/no-image.webp') }}';this.classList.remove('opacity-0');this.parentElement.classList.remove('skeleton');">
                        </div>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="font-bold text-lg mb-1 leading-snug">{{ $product->name }}</h3>
                            <p class="font-bold text-xl mb-4 text-gray-900">{{ $product->formatted_price }}</p>
                            <a href="{{ route('product.show', $product->slug) }}"
                                class=" text-center mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">
                                Cek Produk
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </main>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const headers = document.querySelectorAll('.accordion-header');

                headers.forEach(header => {
                    header.addEventListener('click', () => {
                        const content = header.nextElementSibling;
                        const icon = header.querySelector('i');

                        content.classList.toggle('collapsed');

                        icon.classList.toggle('rotate-icon');
                    });
                });

                const mobileFilterBtn = document.getElementById('mobileFilterBtn');
                const sidebarFilter = document.getElementById('sidebarFilter');

                mobileFilterBtn.addEventListener('click', () => {
                    sidebarFilter.classList.toggle('hidden');
                    sidebarFilter.classList.toggle('flex');
                });
            });
        </script>
    @endpush
</x-app-layout>
