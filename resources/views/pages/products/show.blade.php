<x-app-layout>
    <main class="container mx-auto px-4 py-8 md:py-12 flex-grow">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            <div class="w-full lg:w-1/2">
                <div class="relative w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden group">
                    <div id="sliderImage" class="w-full h-full">
                        <img src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&w=1000&q=80"
                            class="w-full h-full object-cover transition-opacity duration-500" alt="Product Image">
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
                <h1 class="text-3xl md:text-4xl font-bold text-black mb-6">Asus VivoBook X421EQ</h1>

                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    AMD Ryzen 3 7320U (4 Cores – 8 Threads) 2.4GHz Upto 4.1GHz | Layar 14" FHD | Radeon Graphics | SSD
                    512GB | RAM 8GB LPDDR5 | WiFi | Bluetooth | Webcam | Backlight Keyboard | Fingerprint | OS Windows
                    11 Original | Office Home & Student | Garansi Resmi Asus 2 Tahun
                </p>

                <div class="mb-2">
                    <span class="text-4xl md:text-5xl font-bold text-black">Rp4.250.000</span>
                </div>

                <div class="text-sm font-bold text-black mb-10">
                    *stok terbatas
                </div>

                <div class="flex gap-8 items-center">
                    <a href="#" class="transform hover:scale-110 transition duration-300">
                        <i class="fa-brands fa-whatsapp text-whatsapp text-6xl"></i>
                    </a>

                    <a href="#"
                        class="flex flex-col items-center gap-1 group transform hover:scale-110 transition duration-300">
                        <div class="text-shopee text-6xl">
                            <i class="fa-solid fa-bag-shopping"></i>
                        </div>
                        <span class="text-xs font-bold text-shopee">Shopee</span>
                    </a>

                    <a href="#"
                        class="flex flex-col items-center gap-1 group transform hover:scale-110 transition duration-300">
                        <div class="text-tokopedia text-6xl">
                            <i class="fa-solid fa-shop"></i>
                        </div>
                        <span class="text-xs font-bold text-tokopedia">tokopedia</span>
                    </a>
                </div>
            </div>

        </div>
    </main>
</x-app-layout>
