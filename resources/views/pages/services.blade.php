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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <div
                class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-blue-50 p-4 rounded-full mb-4">
                    <img src="{{ asset('assets/img/service-page/instal.png') }}" alt="Instalasi"
                        class="w-12 h-12 object-contain">
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Instalasi</h3>
                <p class="text-gray-600 leading-relaxed">
                    Kami melayani instalasi sistem operasi, driver, dan aplikasi sesuai kebutuhan Anda.
                </p>
            </div>
            <div
                class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-blue-50 p-4 rounded-full mb-4">
                    <img src="{{ asset('assets/img/service-page/upgrade.png') }}" alt="Upgrade"
                        class="w-12 h-12 object-contain">
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Upgrade/Downgrade</h3>
                <p class="text-gray-600 leading-relaxed">
                    Ingin meningkatkan performa laptop atau menyesuaikan sistem operasi? Kami menyediakan layanan
                    upgrade RAM, SSD, serta downgrade/upgrade OS dengan proses profesional dan bergaransi.
                </p>
            </div>
            <div
                class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-blue-50 p-4 rounded-full mb-4">
                    <img src="{{ asset('assets/img/service-page/maintenance.png') }}" alt="Maintenance"
                        class="w-12 h-12 object-contain">
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Maintenance</h3>
                <p class="text-gray-600 leading-relaxed">
                    Layanan perawatan laptop seperti cleaning, ganti thermal paste, cek hardware, dan optimasi performa.
                </p>
            </div>
            <div
                class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-blue-50 p-4 rounded-full mb-4">
                    <img src="{{ asset('assets/img/service-page/sparepart.png') }}" alt="Sparepart"
                        class="w-12 h-12 object-contain">
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Sparepart</h3>
                <p class="text-gray-600 leading-relaxed">
                    Menyediakan berbagai sparepart laptop original dan berkualitas seperti keyboard, layar, baterai,
                    RAM, SSD, dan komponen lainnya dengan harga kompetitif.
                </p>
            </div>
            <div
                class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-blue-50 p-4 rounded-full mb-4">
                    <img src="{{ asset('assets/img/service-page/software.png') }}" alt="Software"
                        class="w-12 h-12 object-contain">
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Software</h3>
                <p class="text-gray-600 leading-relaxed">
                    Melayani instalasi software original maupun kebutuhan aplikasi kerja, desain, hingga gaming dengan
                    konfigurasi yang optimal dan aman.
                </p>
            </div>
            <div
                class="bg-white rounded-xl shadow-md border border-gray-100 p-6 flex flex-col items-center text-center hover:shadow-xl transition-shadow duration-300">
                <div class="bg-blue-50 p-4 rounded-full mb-4">
                    <img src="{{ asset('assets/img/service-page/garansi.png') }}" alt="Garansi"
                        class="w-12 h-12 object-contain">
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Garansi</h3>
                <p class="text-gray-600 leading-relaxed">
                    Setiap layanan dan sparepart yang kami berikan dilengkapi garansi untuk memastikan kenyamanan dan
                    kepuasan pelanggan.
                </p>
            </div>

        </div>
    </main>
</x-app-layout>
