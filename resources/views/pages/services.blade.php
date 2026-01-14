<x-app-layout>
    <main class="container mx-auto px-4 py-8 md:py-12 flex-grow">

        <div class="flex flex-col md:flex-row gap-6 mb-8">

            <a href="{{ $settings->getWhatsappUrl('Halo Bengawan Computer, saya ingin mengecek status service perangkat saya. Mohon informasinya.') }}"
                target="_blank"
                class="flex-1 bg-brand-dark text-white rounded-xl py-4 px-6 flex items-center justify-center gap-4 shadow-lg hover:bg-blue-900 transition group">
                <div
                    class="border-2 border-white/30 rounded-full p-2 w-12 h-12 flex items-center justify-center group-hover:bg-white/10 transition">
                    <i class="fa-solid fa-phone-volume text-xl"></i>
                </div>
                <span class="font-bold text-lg">Check Status Service Anda</span>
            </a>

            <a href="{{ $settings->getWhatsappUrl('Halo Bengawan Computer, saya mengalami kendala pada laptop saya dan ingin berkonsultasi mengenai perbaikannya. Terima kasih.') }}"
                target="_blank"
                class="flex-1 bg-brand-dark text-white rounded-xl py-4 px-6 flex items-center justify-center gap-4 shadow-lg hover:bg-blue-900 transition group">
                <div
                    class="border-2 border-white/30 rounded-full p-2 w-12 h-12 flex items-center justify-center group-hover:bg-white/10 transition">
                    <i class="fa-solid fa-phone-volume text-xl"></i>
                </div>
                <span class="font-bold text-lg">Konsultasikan Masalah Laptopmu</span>
            </a>

        </div>

        <div class="w-full bg-placeholder-gray rounded-lg shadow-inner h-64 md:h-[500px]"></div>

    </main>
</x-app-layout>
