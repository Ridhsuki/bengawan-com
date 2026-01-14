<x-app-layout>
    <section class="container mx-auto px-4 py-6">
        <div class="relative w-full h-64 md:h-[400px] bg-gray-200 rounded-lg overflow-hidden group">

            <div id="carousel" class="flex transition-transform duration-500 h-full">

                @forelse($settings->banner_list as $index => $banner)
                    <div class="min-w-full h-full relative">
                        @if ($banner['url'])
                            <a href="{{ $banner['url'] }}" target="_blank" class="block w-full h-full">
                            @else
                                <div class="block w-full h-full">
                        @endif

                        <div class="skeleton w-full h-full relative overflow-hidden bg-gray-300 animate-pulse">

                            <img src="{{ $banner['image_url'] }}"
                                alt="{{ $banner['title'] ?? 'Banner ' . ($index + 1) }}" loading="lazy" decoding="async"
                                class="w-full h-full object-cover transition-opacity duration-700 opacity-0"
                                onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('skeleton', 'animate-pulse');"
                                onerror="this.onerror=null; this.src='{{ asset('assets/img/no-image.webp') }}'; this.classList.remove('opacity-0'); this.parentElement.classList.remove('skeleton', 'animate-pulse');">

                            @if ($banner['title'])
                                <div
                                    class="absolute bottom-10 left-4 md:left-10 bg-black/50 text-white px-6 py-3 rounded-lg backdrop-blur-lg z-10 transform transition-transform duration-300 hover:scale-105 hover:shadow-lg">
                                    <h3
                                        class="text-xl md:text-3xl font-extrabold tracking-wide leading-tight text-shadow-lg">
                                        {{ $banner['title'] }}
                                    </h3>
                                </div>
                            @endif
                        </div>

                        @if ($banner['url'])
                            </a>
                        @else
                    </div>
                @endif
            </div>
        @empty
            <div
                class="min-w-full h-full bg-gray-600 flex items-center justify-center text-white/20 text-4xl font-bold">
                NO BANNER
            </div>
            @endforelse
        </div>

        @if (count($settings->banner_list) > 1)
            <button id="prevBtn"
                class="cursor-pointer absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 text-3xl focus:outline-none z-20 bg-black/20 hover:bg-black/40 rounded-full p-2 transition">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button id="nextBtn"
                class="cursor-pointer absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 text-3xl focus:outline-none z-20 bg-black/20 hover:bg-black/40 rounded-full p-2 transition">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                @foreach ($settings->banner_list as $index => $banner)
                    <button
                        class="w-2 h-2 rounded-full bg-white transition-opacity duration-300 dot-indicator {{ $index === 0 ? 'opacity-100' : 'opacity-50' }}"
                        data-index="{{ $index }}">
                    </button>
                @endforeach
            </div>
        @endif
        </div>
    </section>

    <section class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-brand-blue">Produk Terbaru</h2>
            <a href="{{ route('products.index') }}"
                class="text-brand-blue hover:underline font-medium decoration-2 underline-offset-4">Lihat
                Semua</a>
        </div>

        <div id="productContainer"
            class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4 no-scrollbar">
            @foreach ($products as $product)
                <div
                    class="min-w-full sm:min-w-[calc(50%-12px)] lg:min-w-[calc(25%-18px)] snap-start bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                    <div class="h-48 skeleton w-full relative overflow-hidden bg-gray-200">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/no-image.webp') }}"
                            alt="{{ $product->name }}" loading="lazy" decoding="async"
                            class="w-full h-full object-cover transition-opacity duration-700 opacity-0"
                            onload="this.classList.remove('opacity-0'); this.parentElement.classList.remove('skeleton');"
                            onerror="this.onerror=null; this.src='{{ asset('assets/img/no-image.webp') }}'; this.classList.remove('opacity-0'); this.parentElement.classList.remove('skeleton');">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-1 line-clamp-2">{{ $product->name }}</h3>
                        <p class="font-bold text-xl mb-4">{{ $product->formatted_price }}</p>
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="text-center mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition">Cek
                            Produk</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center gap-4 mt-8 relative items-center">
            <div class="w-full h-[1px] bg-gray-300 absolute z-0"></div>
            <div class="flex gap-2 bg-white px-4 z-10">
                <button id="prevProductBtn"
                    class="w-8 h-8 flex items-center justify-center bg-brand-blue text-white rounded hover:bg-blue-800 active:bg-blue-300 transition cursor-pointer">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <button id="nextProductBtn"
                    class="w-8 h-8 flex items-center justify-center bg-brand-blue text-white rounded hover:bg-blue-800 active:bg-blue-300 transition cursor-pointer">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>


    </section>

    <section class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div
                class="bg-gray-600 p-8 rounded text-white flex flex-col justify-center h-48 hover:bg-gray-700 transition">
                <h3 class="font-bold text-lg uppercase mb-1">PROMO</h3>
                <p class="text-sm text-gray-200 mb-4">Promo Laptop Terbaru</p>
                <div>
                    <a href="{{ route('discount') }}"
                        class="bg-white text-gray-800 px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-100 transition">
                        Cek disini
                    </a>
                </div>
            </div>

            <div
                class="bg-gray-600 p-8 rounded text-white flex flex-col justify-center h-48 hover:bg-gray-700 transition">
                <h3 class="font-bold text-lg uppercase mb-1">PRICELIST</h3>
                <p class="text-sm text-gray-200 mb-4">Laptop / Notebook</p>
                <div>
                    <a href="{{ route('products.index') }}"
                        class="bg-white text-gray-800 px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-100 transition">
                        Cek disini
                    </a>
                </div>
            </div>

            <div
                class="bg-gray-600 p-8 rounded text-white flex flex-col justify-center h-48 hover:bg-gray-700 transition">
                <h3 class="font-bold text-lg uppercase mb-1">GAMING</h3>
                <p class="text-sm text-gray-200 mb-4">All Laptop GAMING</p>
                <div>
                    <a href="{{ route('products.index') }}?category=laptop-gaming"
                        class="bg-white text-gray-800 px-6 py-2 rounded-full text-sm font-bold hover:bg-gray-100 transition">
                        Cek disini
                    </a>
                </div>
            </div>

        </div>
    </section>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const carousel = document.getElementById('carousel');
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const dots = document.querySelectorAll('.dot-indicator');
                let currentIndex = 0;
                const totalSlides = dots.length;

                if (carousel && prevBtn && nextBtn && dots.length > 0) {
                    function updateCarousel() {
                        carousel.style.transform = `translateX(-${currentIndex * 100}%)`;

                        dots.forEach((dot, index) => {
                            if (index === currentIndex) {
                                dot.classList.remove('opacity-50');
                                dot.classList.add('opacity-100');
                            } else {
                                dot.classList.add('opacity-50');
                                dot.classList.remove('opacity-100');
                            }
                        });
                    }

                    function nextSlide() {
                        currentIndex = (currentIndex + 1) % totalSlides;
                        updateCarousel();
                    }

                    function prevSlide() {
                        currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                        updateCarousel();
                    }

                    nextBtn.addEventListener('click', nextSlide);
                    prevBtn.addEventListener('click', prevSlide);

                    dots.forEach(dot => {
                        dot.addEventListener('click', (e) => {
                            currentIndex = parseInt(e.target.dataset.index);
                            updateCarousel();
                        });
                    });

                    setInterval(nextSlide, 5000);
                }

                const productContainer = document.getElementById('productContainer');
                const prevProductBtn = document.getElementById('prevProductBtn');
                const nextProductBtn = document.getElementById('nextProductBtn');

                if (productContainer && prevProductBtn && nextProductBtn) {
                    const scrollAmount = () => {
                        const cardItem = productContainer.firstElementChild;
                        const gap = 24;
                        return cardItem ? cardItem.clientWidth + gap : 0;
                    };

                    nextProductBtn.addEventListener('click', () => {
                        productContainer.scrollBy({
                            left: scrollAmount(),
                            behavior: 'smooth'
                        });
                    });

                    prevProductBtn.addEventListener('click', () => {
                        productContainer.scrollBy({
                            left: -scrollAmount(),
                            behavior: 'smooth'
                        });
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
