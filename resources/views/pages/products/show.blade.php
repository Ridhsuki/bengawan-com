<x-app-layout>
    <main class="container mx-auto px-4 py-8 md:py-12 flex-grow">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            <div class="w-full lg:w-1/2">
                <div class="relative w-full aspect-[4/3] bg-gray-100 rounded-lg overflow-hidden group">
                    <div id="slider" class="flex w-full h-full transition-transform duration-500">
                        @foreach ($images as $index => $image)
                            <div class="w-full h-full flex-shrink-0 relative skeleton">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}" decoding="async"
                                    class="w-full h-full object-cover opacity-0 transition-opacity duration-700"
                                    onload="
                        this.classList.remove('opacity-0');
                        this.closest('.skeleton')?.classList.remove('skeleton');
                    "
                                    onerror="
                        this.src='{{ asset('assets/img/no-image.webp') }}';
                        this.classList.remove('opacity-0');
                    ">
                            </div>
                        @endforeach
                    </div>

                    @if ($images->count() > 1)
                        <button id="prevBtn" onclick="moveSlide(-1)"
                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-black/70 transition z-30 opacity-0 group-hover:opacity-100 cursor-pointer">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <button id="nextBtn" onclick="moveSlide(1)"
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-black/70 transition z-30 opacity-0 group-hover:opacity-100 cursor-pointer">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>

                        <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-30">
                            @foreach ($images as $index => $img)
                                <div class="slider-dot w-2 h-2 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white/50' }} transition-colors cursor-pointer"
                                    onclick="goToSlide({{ $index }})"></div>
                            @endforeach
                        </div>
                    @endif
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
                    <a href="{{ $product->whatsapp_inquiry_link }}" target="_blank"
                        class="transform hover:scale-110 transition duration-300">
                        <img src="{{ asset('assets/img/WhatsApp.png') }}" alt="WhatsApp" class="h-12 md:h-16">
                    </a>

                    <a href="{{ $product->link_shopee }}"
                        class="flex flex-col items-center gap-1 group transform hover:scale-110 transition duration-300">
                        <img src="{{ asset('assets/img/shopee.png') }}" alt="Shopee" class="h-12 md:h-16">
                    </a>

                    <a href="{{ $product->link_tokopedia }}"
                        class="flex flex-col items-center gap-1 group transform hover:scale-110 transition duration-300">
                        <img src="{{ asset('assets/img/tokopedia-seeklogo.png') }}" alt="Tokopedia"
                            class="h-12 md:h-16">
                    </a>
                </div>
            </div>

        </div>
    </main>
    @push('scripts')
        <script>
            const slider = document.getElementById('slider');
            const slides = slider.children;
            const dots = document.querySelectorAll('.slider-dot');

            const total = slides.length;
            let index = 0;

            function updateSlider() {
                slider.style.transform = `translateX(-${index * 100}%)`;

                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-white', i === index);
                    dot.classList.toggle('bg-white/50', i !== index);
                });
            }

            function moveSlide(step) {
                index = (index + step + total) % total;
                updateSlider();
            }

            function goToSlide(i) {
                index = i;
                updateSlider();
            }
        </script>
    @endpush
</x-app-layout>
