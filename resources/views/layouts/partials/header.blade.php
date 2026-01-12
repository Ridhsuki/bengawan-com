<div class="container mx-auto px-4 py-2 text-xs font-medium text-gray-600">
    Buka setiap hari 08:00 - 21:00 WIB
</div>

<header class="relative z-60 bg-[#6B8AF3] bg-gradient-to-r from-[#6B8AF3] to-[#4361ee] shadow-md py-4">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">

        <a href="{{ route('home') }}">
            <div class="flex items-center">
                <img src="{{ asset('assets/img/logo1.png') }}" alt="Bengawan Komputer Logo">
            </div>
        </a>

        <div class="w-full md:max-w-xl relative" x-data="searchComponent()" x-init="init()" @click.away="show = false"
            x-cloak>

            <input type="text" placeholder="Cari produk disini" x-model="query" @input.debounce.300ms="search"
                class="bg-white w-full py-2.5 px-5 rounded-full outline-none text-gray-700 shadow-sm focus:ring-2 focus:ring-blue-300 transition">

            <button @click.prevent="goToSearch()"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-800 transition cursor-pointer">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </button>

            <div x-show="show && results.length > 0" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                class="absolute z-50 mt-2 w-full bg-white shadow-lg rounded-md max-h-60 overflow-y-auto origin-top will-change-transform will-change-opacity"
                x-cloak class="absolute mt-1 w-full bg-white shadow-lg rounded-md z-50 max-h-60 overflow-y-auto"
                x-cloak>
                <template x-for="product in results" :key="product.slug">
                    <a :href="product.url" class="flex items-center gap-2 p-2 hover:bg-gray-100 transition">
                        <img :src="product.image" class="w-10 h-10 object-cover rounded" alt="" />
                        <div class="flex flex-col text-sm">
                            <span x-text="product.name" class="font-medium text-gray-700"></span>
                            <span x-text="product.price" class="text-gray-500 text-xs"></span>
                        </div>
                    </a>
                </template>
                <div x-show="results.length === 0" class="p-2 text-gray-400 text-sm">
                    Tidak ada produk ditemukan
                </div>
            </div>
        </div>

        <div class="hidden md:flex items-center text-white gap-2">
            @auth
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center gap-2 group">
                    <i class="fa-solid fa-user text-2xl transition group-hover:text-yellow-300"></i>
                    <span class="hidden sm:block text-sm font-medium group-hover:text-yellow-300">
                        Dashboard
                    </span>
                </a>
            @endauth

            @guest
                <a href="{{ route('filament.admin.auth.login') }}" class="flex items-center gap-2 group">
                    <i class="fa-regular fa-user text-2xl transition group-hover:text-yellow-300"></i>
                </a>
                <div class="text-xs text-right leading-tight">
                    <div class="font-bold">WhatsApp</div>
                    <div>085799599723</div>
                </div>
            @endguest
        </div>
    </div>
</header>
