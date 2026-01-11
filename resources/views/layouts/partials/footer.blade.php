<footer class="bg-footer-bg pt-12 pb-6 mt-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

            <div>
                <div class="flex items-center md:text-2xl mb-4">
                    <img src="{{ asset('assets/img/logo2.png') }}" alt="Bengawan Komputer Solo" loading="lazy">
                </div>
                <p class="text-sm text-gray-700 leading-relaxed mb-4">
                    Jl. Al Ikhlas, Mendungan, Pabelan,<br>
                    Kec. Kartasura, Kab. Sukoharjo<br>
                    Telp(WA) : 085799599723<br>
                    Instagram : @laptopsecondsolo<br>
                    Facebook : @BENGAWANKOMPUTER<br>
                    Tiktok : @laptopsecondsoloraya
                </p>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-4">Navigation</h4>
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="{{ request()->routeIs('home') ? 'text-brand-blue font-bold' : '' }}">
                        <a href="{{ route('home') }}" class="hover:text-brand-blue transition">Home</a>
                    </li>
                    <li class="{{ request()->routeIs('about') ? 'text-brand-blue font-bold' : '' }}">
                        <a href="{{ route('about') }}" class="hover:text-brand-blue transition">Tentang Kami</a>
                    </li>
                    <li class="{{ request()->routeIs('service') ? 'text-brand-blue font-bold' : '' }}">
                        <a href="{{ route('service') }}" class="hover:text-brand-blue transition">Service</a>
                    </li>
                    <li class="{{ request()->routeIs('discount') ? 'text-brand-blue font-bold' : '' }}">
                        <a href="{{ route('discount') }}" class="hover:text-brand-blue transition">Diskon</a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-2">Beri Masukan</h4>
                <p class="text-xs text-gray-600 mb-3">Bantu kami jadi lebih baik! Sampaikan kritik dan saran Anda di
                    sini</p>

                <form class="flex items-center border border-gray-400 rounded-full bg-transparent p-1">
                    <input type="text" placeholder="Tulis masukan kalian disini"
                        class="bg-transparent flex-grow px-4 py-1 text-sm outline-none text-gray-700 placeholder-gray-400">
                    <button type="button"
                        class="bg-white text-brand-blue text-sm font-bold px-6 py-1.5 rounded-full shadow hover:bg-gray-50 transition">
                        Kirim
                    </button>
                </form>
            </div>

        </div>

        <div class="border-t border-gray-300 pt-6 text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} Bengawan Komputer
        </div>
    </div>
</footer>
