<x-app-layout>
    <main class="flex-grow flex items-center">
        <section class="container mx-auto px-4 py-12 md:py-24">
            <div class="flex flex-col md:flex-row items-center justify-center gap-10 md:gap-20">

                <div class="w-full md:w-1/2 flex justify-center md:justify-end">
                    <div class="flex items-center select-none opacity-90 scale-90 md:scale-100">
                        <img src="{{ asset('assets/img/about-logo.png') }}" alt="About Bengawan Komputer"
                            class="w-72 md:w-96 lg:w-full" loading="lazy">
                    </div>
                </div>

                <div class="w-full md:w-1/2 max-w-lg">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 uppercase mb-4 leading-tight">
                        {{ $settings->about_title ?? 'Tentang Bengawan Komputer' }}
                    </h1>
                    <p class="text-gray-700 text-base leading-relaxed mb-8">
                        {!! nl2br(e($settings->about_desc ?? '-')) !!}
                    </p>

                    <a href="{{ $settings->getWhatsappUrl('Halo Bengawan Computer, saya melihat penawaran *Diskon Spesial* di website. Boleh diinfokan promo atau potongan harga yang sedang berlaku saat ini? Terima kasih.') }}"
                        target="_blank"
                        class="inline-flex items-center gap-3 bg-brand-dark text-white font-medium px-8 py-3 rounded-full hover:bg-blue-900 transition shadow-lg">
                        <i class="fa-solid fa-phone-volume"></i>
                        Hubungi Kami dan Dapatkan Diskon !
                    </a>
                </div>

            </div>
        </section>
    </main>
</x-app-layout>
