<x-app-layout>
    <section class="container mx-auto px-4 py-6">
        <div class="relative w-full h-64 md:h-[400px] bg-gray-600 rounded-lg overflow-hidden group">

            <div id="carousel" class="flex transition-transform duration-500 h-full">
                <div
                    class="min-w-full h-full bg-gray-600 flex items-center justify-center text-white/20 text-4xl font-bold">
                    BANNER 1
                </div>
                <div
                    class="min-w-full h-full bg-gray-700 flex items-center justify-center text-white/20 text-4xl font-bold">
                    BANNER 2
                </div>
                <div
                    class="min-w-full h-full bg-gray-500 flex items-center justify-center text-white/20 text-4xl font-bold">
                    BANNER 3
                </div>
            </div>

            <button id="prevBtn"
                class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 text-3xl focus:outline-none">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button id="nextBtn"
                class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 text-3xl focus:outline-none">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                <button class="w-2 h-2 rounded-full bg-white opacity-100 dot-indicator" data-index="0"></button>
                <button class="w-2 h-2 rounded-full bg-white opacity-50 dot-indicator" data-index="1"></button>
                <button class="w-2 h-2 rounded-full bg-white opacity-50 dot-indicator" data-index="2"></button>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-brand-blue">Produk Terbaru</h2>
            <a href="#" class="text-brand-blue hover:underline font-medium decoration-2 underline-offset-4">Lihat
                Semua</a>
        </div>

        <div id="productContainer"
            class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-4 no-scrollbar">

            <div
                class="min-w-full sm:min-w-[calc(50%-12px)] lg:min-w-[calc(25%-18px)] snap-start bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-48 bg-gray-200 w-full relative">
                    <img src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&w=500&q=80"
                        alt="Laptop" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg mb-1 line-clamp-2">Asus VivoBook X421EQ</h3>
                    <p class="font-bold text-xl mb-4">Rp4.250.000</p>
                    <button
                        class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition">Cek
                        Produk</button>
                </div>
            </div>

            <div
                class="min-w-full sm:min-w-[calc(50%-12px)] lg:min-w-[calc(25%-18px)] snap-start bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-48 bg-gray-200 w-full relative">
                    <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=500&q=80"
                        alt="Laptop" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg mb-1 line-clamp-2">Acer Aspire A314 - 23M</h3>
                    <p class="font-bold text-xl mb-4">Rp3.650.000</p>
                    <button
                        class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition">Cek
                        Produk</button>
                </div>
            </div>

            <div
                class="min-w-full sm:min-w-[calc(50%-12px)] lg:min-w-[calc(25%-18px)] snap-start bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-48 bg-gray-200 w-full relative">
                    <img src="https://images.unsplash.com/photo-1593642632823-8f78536788c6?auto=format&fit=crop&w=500&q=80"
                        alt="Laptop" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg mb-1 line-clamp-2">Asus Rog Strix G531GT</h3>
                    <p class="font-bold text-xl mb-4">Rp7.950.000</p>
                    <button
                        class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition">Cek
                        Produk</button>
                </div>
            </div>

            <div
                class="min-w-full sm:min-w-[calc(50%-12px)] lg:min-w-[calc(25%-18px)] snap-start bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-48 bg-gray-200 w-full relative">
                    <img src="https://images.unsplash.com/photo-1588872657578-a83f79636e62?auto=format&fit=crop&w=500&q=80"
                        alt="Laptop" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg mb-1 line-clamp-2">Lenovo Ideapad Gaming 3</h3>
                    <p class="font-bold text-xl mb-4">Rp8.650.000</p>
                    <button
                        class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition">Cek
                        Produk</button>
                </div>
            </div>

            <div
                class="min-w-full sm:min-w-[calc(50%-12px)] lg:min-w-[calc(25%-18px)] snap-start bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-48 bg-gray-200 w-full relative">
                    <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=500&q=80"
                        alt="Laptop" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg mb-1 line-clamp-2">MacBook Pro M1 2020</h3>
                    <p class="font-bold text-xl mb-4">Rp14.250.000</p>
                    <button
                        class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition">Cek
                        Produk</button>
                </div>
            </div>

            <div
                class="min-w-full sm:min-w-[calc(50%-12px)] lg:min-w-[calc(25%-18px)] snap-start bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition flex flex-col">
                <div class="h-48 bg-gray-200 w-full relative">
                    <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=500&q=80"
                        alt="Laptop" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="font-bold text-lg mb-1 line-clamp-2">HP Pavilion 15 Ryzen</h3>
                    <p class="font-bold text-xl mb-4">Rp5.950.000</p>
                    <button
                        class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition">Cek
                        Produk</button>
                </div>
            </div>

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
                    <a href="#"
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
                    <a href="#"
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
                    <a href="#"
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
