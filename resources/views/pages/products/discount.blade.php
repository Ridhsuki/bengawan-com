<x-app-layout>
    <main class="container mx-auto px-4 py-8 flex-grow">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div
                    class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                    <a href="{{ route('products.show', $product->slug) }}" class="block relative">
                        <div class="h-48 skeleton bg-gray-200 w-full relative overflow-hidden">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/no-image.webp') }}"
                                alt="{{ $product->name }}" loading="lazy" decoding="async"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-600"
                                onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('skeleton');"
                                onerror="this.onerror=null; this.src='{{ asset('assets/img/no-image.webp') }}'; this.classList.remove('opacity-0'); this.parentElement.classList.remove('skeleton');">
                            @if ($product->has_discount)
                                <div
                                    class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                                    {{ $product->discount_percentage }}% OFF
                                </div>
                            @endif
                        </div>
                    </a>
                    <div class="p-4 flex flex-col flex-grow">
                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <h3
                                class="font-bold text-lg mb-2 leading-snug line-clamp-2 hover:text-brand-blue transition-colors">
                                {{ $product->name }}
                            </h3>
                        </a>
                        <div class="flex items-center gap-2 mb-6">
                            <span
                                class="text-gray-400 text-sm line-through decoration-gray-400">{{ $product->formatted_price }}</span>
                            <span
                                class="font-bold text-lg text-gray-900">{{ $product->formatted_discount_price }}</span>
                        </div>

                        <div class="mt-auto flex items-end justify-center gap-6 px-2">
                            <a href="#" class="flex flex-col items-center gap-1 group/icon">
                                <img src="{{ asset('assets/img/WhatsApp.png') }}" alt="whatsapp logo"
                                    class="object-contain group-hover/icon:scale-110 transition">
                            </a>
                            <a href="{{ $product->link_shopee }}" target="_blank"
                                class="flex flex-col items-center gap-1 group/icon">
                                <img src="{{ asset('assets/img/shopee.png') }}" alt="shopee logo"
                                    class="object-contain group-hover/icon:scale-110 transition">
                            </a>
                            <a href="{{ $product->link_tokopedia }}" target="_blank"
                                class="flex flex-col items-center gap-1 group/icon">
                                <img src="{{ asset('assets/img/tokopedia-seeklogo.png') }}" alt="tokopedia logo"
                                    class="object-contain group-hover/icon:scale-110 transition">
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="bg-gray-100 p-6 rounded-full mb-4">
                        <i class="fa-solid fa-tag text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700">Belum Ada Promo</h3>
                    <p class="text-gray-500 mb-6">Nantikan penawaran menarik kami segera!</p>
                    <a href="{{ route('products.index') }}" class="text-brand-blue hover:underline font-bold">
                        Lihat Semua Produk
                    </a>
                </div>
            @endforelse
        </div>
        <div class="flex justify-center mt-4">
            {{ $products->links() }}
        </div>
    </main>
</x-app-layout>
