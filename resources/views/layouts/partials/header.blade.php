<div class="container mx-auto px-4 py-2 text-xs font-medium text-gray-600">
    Buka setiap hari 08:00 - 21:00 WIB
</div>

<header class="bg-[#6B8AF3] bg-gradient-to-r from-[#6B8AF3] to-[#4361ee] shadow-md py-4">
    <div class="container mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">

        <a href="{{ route('home') }}">
            <div class="flex items-center">
                <img src="{{ asset('assets/img/logo1.png') }}" alt="Bengawan Komputer Logo">
            </div>
        </a>

        <div class="w-full md:max-w-xl relative">
            <input type="text" placeholder="Cari produk disini"
                class="bg-white w-full py-2.5 px-5 rounded-full outline-none text-gray-700 shadow-sm focus:ring-2 focus:ring-blue-300 transition">
            <button
                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-800 transition">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </button>
        </div>

        <div class="hidden md:flex items-center text-white gap-2">
            <a href="{{ route('filament.admin.auth.login') }}">
                <i class="fa-regular fa-user text-2xl"></i>
            </a>
            <div class="text-xs text-right leading-tight">
                <div class="font-bold">WhatsApp</div>
                <div>085799599723</div>
            </div>
        </div>
    </div>
</header>
