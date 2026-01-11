<x-app-layout>
    <main class="container mx-auto px-4 py-8 md:py-12 flex-grow">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            <div class="w-full lg:w-1/2">
                <div class="relative w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden group">
                    <div id="sliderImage" class="w-full h-full">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/500x300.svg?text=No+Image' }}"
                            class="w-full h-full object-cover transition-opacity duration-500" alt="{{ $product->name }}">
                    </div>

                    <button id="prevBtn"
                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-black/70 transition">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <button id="nextBtn"
                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-black/70 transition">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="w-full lg:w-1/2 flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl font-bold text-black mb-6">{{ $product->name }}</h1>

                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    {{ $product->description }}
                </p>

                <div class="mb-2">
                    <span class="text-4xl md:text-5xl font-bold text-black">
                        @if ($product->has_discount)
                            <span class="line-through text-gray-500">{{ $product->formattedPrice }}</span>
                            <span class="text-red-500">{{ $product->formattedDiscountPrice }}</span>
                        @else
                            {{ $product->formattedPrice }}
                        @endif
                    </span>
                </div>

                <div class="text-sm font-bold text-black mb-10">
                    *stok terbatas
                </div>

                <div class="flex gap-8 items-center">
                    <a href="https://wa.me/<?= $product->whatsapp_link ?>"
                        class="transform hover:scale-110 transition duration-300">
                        <img src="{{ asset('assets/img/WhatsApp.png') }}" alt="WhatsApp" class="h-12 md:h-16">
                    </a>

                    <a href="{{ $product->link_shopee }}"
                        class="flex flex-col items-center gap-1 group transform hover:scale-110 transition duration-300">
                        <img src="{{ asset('assets/img/shopee.png') }}" alt="Shopee" class="h-12 md:h-16">
                    </a>

                    <a href="{{ $product->link_tokopedia }}"
                        class="flex flex-col items-center gap-1 group transform hover:scale-110 transition duration-300">
                        <img src="{{ asset('assets/img/tokopedia-seeklogo 2.png') }}" alt="Tokopedia"
                            class="h-12 md:h-16">
                    </a>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
