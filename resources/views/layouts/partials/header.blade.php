<div class="container mx-auto px-4 py-4 relative z-[60]">

    <div class="mb-3 text-gray-800 font-medium text-sm">
        Buka setiap hari 08:00 - 21:00 WIB
    </div>

    <div
        class="w-full bg-[#6B8AF3] bg-gradient-to-r from-[#6B8AF3] to-[#172D9D] rounded-2xl px-6 py-3 flex flex-col md:flex-row items-center justify-between shadow-lg gap-4 relative">

        <a href="{{ route('home') }}">
            <div class="flex items-center">
                <img src="{{ asset('assets/img/logo1.png') }}" alt="Bengawan Komputer Logo">
            </div>
        </a>


        <div class="flex-1 w-full max-w-2xl px-2">

            <div class="w-full md:max-w-xl relative" x-data="searchComponent()" x-init="init()"
                @click.away="show = false" x-cloak>

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
                    class="absolute top-full left-0 mt-2 w-full bg-white shadow-2xl rounded-xl max-h-80 overflow-y-auto z-[70] border border-gray-100">

                    <template x-for="product in results" :key="product.slug">
                        <a :href="product.url"
                            class="flex items-center gap-3 p-3 hover:bg-gray-50 transition border-b last:border-0 border-gray-100">
                            <img :src="product.image" class="w-12 h-12 object-cover rounded-md" alt="" />
                            <div class="flex flex-col text-left">
                                <span x-text="product.name"
                                    class="font-medium text-gray-800 text-sm line-clamp-1"></span>
                                <span x-text="product.price" class="text-blue-600 font-bold text-xs mt-0.5"></span>
                            </div>
                        </a>
                    </template>
                    <div x-show="results.length === 0" class="p-4 text-center text-gray-500 text-sm">
                        Tidak ada produk ditemukan
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-shrink-0">
            <div class="hidden md:flex items-center text-white gap-2">

                @auth
                    <a href="{{ route('filament.admin.pages.dashboard') }}" class="flex items-center gap-2 group">
                        <i class="fa-solid fa-user text-2xl transition group-hover:text-yellow-300"></i>
                        <span class="hidden sm:block text-sm font-medium group-hover:text-yellow-300">
                            Dashboard
                        </span>
                    </a>
                @else
                    <a href="{{ route('filament.admin.auth.login') }}" class="flex items-center gap-2 group">
                        <i class="fa-regular fa-user text-2xl transition group-hover:text-yellow-300"></i>
                    </a>
                    <a href="{{ $settings->getWhatsappUrl('Halo Bengawan Computer.') }}" target="_blank"
                        class="text-xs text-right leading-tight">
                        <div class="font-bold">WhatsApp</div>
                        <div class="whitespace-nowrap">{{ $settings->phone ?? '-' }}</div>
                    </a>
                @endauth

            </div>
        </div>

    </div>
</div>
