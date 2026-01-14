<nav class="bg-white border-b border-gray-200 py-4 relative z-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center justify-between">

            <a href="{{ route('products.index') }}"
                class="bg-brand-blue text-white px-4 md:px-6 py-2 rounded-2xl font-medium flex items-center gap-2 hover:bg-blue-800 transition shadow-sm text-sm md:text-base">
                <i class="fa-solid fa-bars"></i>
                <span class="hidden sm:inline">Kategori Produk</span>
            </a>

            <ul class="hidden md:flex gap-8 text-gray-700 font-medium">
                <li class="{{ request()->routeIs('about') ? 'relative group' : '' }}">
                    <a href="{{ route('about') }}"
                        class="{{ request()->routeIs('about') ? 'text-gray-900 font-semibold' : 'hover:text-brand-blue transition' }}">
                        Tentang kami
                    </a>
                    @if (request()->routeIs('about'))
                        <div class="absolute -bottom-2 left-0 w-1/2 h-[3px] bg-brand-blue rounded-full"></div>
                    @endif
                </li>
                <li class="{{ request()->routeIs('service') ? 'relative group' : '' }}">
                    <a href="{{ route('service') }}"
                        class="{{ request()->routeIs('service') ? 'text-gray-900 font-semibold' : 'hover:text-brand-blue transition' }}">
                        Service
                    </a>
                    @if (request()->routeIs('service'))
                        <div class="absolute -bottom-2 left-0 w-1/2 h-[3px] bg-brand-blue rounded-full"></div>
                    @endif
                </li>
                <li class="{{ request()->routeIs('discount') ? 'relative group' : '' }}">
                    <a href="{{ route('discount') }}"
                        class="{{ request()->routeIs('discount') ? 'text-gray-900 font-semibold' : 'hover:text-brand-blue transition' }}">
                        Diskon
                    </a>
                    @if (request()->routeIs('discount'))
                        <div class="absolute -bottom-2 left-0 w-1/2 h-[3px] bg-brand-blue rounded-full"></div>
                    @endif
                </li>
            </ul>


            <div class="flex items-center gap-2">
                <a href="{{ $settings->google_maps_link ?? '#' }}" target="_blank"
                    class="bg-brand-blue text-white px-4 md:px-6 py-2 rounded-2xl font-medium flex items-center gap-2 hover:bg-blue-800 transition shadow-sm text-sm md:text-base">
                    <i class="fa-solid fa-location-dot"></i>
                    <span class="hidden sm:inline">Lokasi Toko</span>
                </a>
                <button id="mobileMenuBtn" class="md:hidden text-gray-700 text-2xl px-2 focus:outline-none">
                    <i class="fa-solid fa-bars-staggered"></i>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="hidden md:hidden mt-4 border-t border-gray-100 pt-4 animate-fade-in-down">
            <ul class="flex flex-col gap-4 text-gray-700 font-medium text-center">
                <li class="{{ request()->routeIs('about') ? 'bg-gray-50 text-brand-blue' : '' }}">
                    <a href="{{ route('about') }}"
                        class="block py-2 hover:text-brand-blue hover:bg-gray-50 rounded transition">Tentang kami</a>
                </li>
                <li class="{{ request()->routeIs('service') ? 'bg-gray-50 text-brand-blue' : '' }}">
                    <a href="{{ route('service') }}"
                        class="block py-2 hover:text-brand-blue hover:bg-gray-50 rounded transition">Service</a>
                </li>
                <li class="{{ request()->routeIs('discount') ? 'bg-gray-50 text-brand-blue' : '' }}">
                    <a href="{{ route('discount') }}"
                        class="block py-2 hover:text-brand-blue hover:bg-gray-50 rounded transition">Diskon</a>
                </li>
                @auth
                    <li>
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                            class="block py-2 hover:text-brand-blue hover:bg-gray-50 rounded transition">
                            <i class="fa-solid fa-user text-sm transition group-hover:text-yellow-300"></i> Dashboard</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
