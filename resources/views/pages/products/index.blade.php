<x-app-layout>
    <div class="container mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8 items-start">
        <button id="mobileFilterBtn"
            class="lg:hidden w-full bg-white border border-gray-300 py-2 rounded-lg font-bold text-gray-700 shadow-sm mb-4">
            <i class="fa-solid fa-filter mr-2"></i> Filter Produk
        </button>

        <aside id="sidebarFilter" class="w-full lg:w-1/4 flex-col gap-6 hidden lg:flex">

            <div class="bg-white rounded-3xl shadow-lg p-6">
                <button
                    class="accordion-header w-full flex justify-between items-center text-gray-600 font-bold text-lg mb-4 pb-2 border-b-2 border-gray-100">
                    <span>Katalog</span>
                    <i class="fa-solid fa-chevron-down transition-transform duration-300"></i>
                </button>
                <div class="accordion-content">
                    <ul class="space-y-3 text-gray-500 font-medium">
                        <li><a href="#" class="text-brand-blue font-bold">Brand Asus</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Brand Lenovo</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Brand HP</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Brand Acer</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Brand Dell</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Brand Axioo</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Brand Advan</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Brand MSI</a></li>
                        <li><a href="#" class="hover:text-brand-blue transition">Lain-lain</a></li>
                    </ul>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-lg p-6">
                <button
                    class="accordion-header w-full flex justify-between items-center text-gray-600 font-bold text-lg mb-4 pb-2 border-b-2 border-gray-100">
                    <span>Range Harga</span>
                    <i class="fa-solid fa-chevron-down transition-transform duration-300"></i>
                </button>
                <div class="accordion-content">
                    <div class="flex flex-col gap-3">
                        <button
                            class="border border-gray-400 text-gray-500 rounded-full py-1.5 px-4 text-sm font-medium hover:bg-brand-blue hover:text-white hover:border-brand-blue transition text-center">
                            &lt;2.000.000
                        </button>
                        <button
                            class="border border-gray-400 text-gray-500 rounded-full py-1.5 px-4 text-sm font-medium hover:bg-brand-blue hover:text-white hover:border-brand-blue transition text-center">
                            &lt;5.000.000
                        </button>
                        <button
                            class="border border-gray-400 text-gray-500 rounded-full py-1.5 px-4 text-sm font-medium hover:bg-brand-blue hover:text-white hover:border-brand-blue transition text-center">
                            &lt;10.000.000
                        </button>
                        <button
                            class="border border-gray-400 text-gray-500 rounded-full py-1.5 px-4 text-sm font-medium hover:bg-brand-blue hover:text-white hover:border-brand-blue transition text-center">
                            &lt;20.000.000
                        </button>
                        <button
                            class="border border-gray-400 text-gray-500 rounded-full py-1.5 px-4 text-sm font-medium hover:bg-brand-blue hover:text-white hover:border-brand-blue transition text-center">
                            &gt;20.000.000
                        </button>
                    </div>
                </div>
            </div>

        </aside>

        <main class="w-full lg:w-3/4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                <div
                    class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                    <div class="h-48 bg-gray-200 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&w=500&q=80"
                            alt="Laptop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-1 leading-snug">Asus VivoBook X421EQ</h3>
                        <p class="font-bold text-xl mb-4 text-gray-900">Rp4.250.000</p>
                        <button
                            class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">
                            Cek Produk
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                    <div class="h-48 bg-gray-200 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1593642632823-8f78536788c6?auto=format&fit=crop&w=500&q=80"
                            alt="Laptop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-1 leading-snug">Asus Rog Strix G531GT</h3>
                        <p class="font-bold text-xl mb-4 text-gray-900">Rp7.950.000</p>
                        <button
                            class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">
                            Cek Produk
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                    <div class="h-48 bg-gray-200 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1588872657578-a83f79636e62?auto=format&fit=crop&w=500&q=80"
                            alt="Laptop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-1 leading-snug">Asus VivoBook X421EQ</h3>
                        <p class="font-bold text-xl mb-4 text-gray-900">Rp4.250.000</p>
                        <button
                            class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">
                            Cek Produk
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                    <div class="h-48 bg-gray-200 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&w=500&q=80"
                            alt="Laptop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-1 leading-snug">Asus VivoBook X421EQ</h3>
                        <p class="font-bold text-xl mb-4 text-gray-900">Rp4.250.000</p>
                        <button
                            class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">
                            Cek Produk
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                    <div class="h-48 bg-gray-200 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1544731612-de7f96afe55f?auto=format&fit=crop&w=500&q=80"
                            alt="Laptop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-1 leading-snug">Asus Rog Strix G531GT</h3>
                        <p class="font-bold text-xl mb-4 text-gray-900">Rp7.950.000</p>
                        <button
                            class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">
                            Cek Produk
                        </button>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-md border border-gray-100 overflow-hidden hover:shadow-xl transition flex flex-col group">
                    <div class="h-48 bg-gray-200 w-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=500&q=80"
                            alt="Laptop"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="font-bold text-lg mb-1 leading-snug">Asus VivoBook X421EQ</h3>
                        <p class="font-bold text-xl mb-4 text-gray-900">Rp4.250.000</p>
                        <button
                            class="mt-auto w-full bg-brand-blue text-white py-2 rounded-lg font-medium hover:bg-blue-800 transition shadow-md">
                            Cek Produk
                        </button>
                    </div>
                </div>

            </div>
        </main>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Logic Accordion Sidebar
                const headers = document.querySelectorAll('.accordion-header');

                headers.forEach(header => {
                    header.addEventListener('click', () => {
                        const content = header.nextElementSibling;
                        const icon = header.querySelector('i');

                        content.classList.toggle('collapsed');
                        icon.classList.toggle('rotate-icon');
                    });
                });

                // Mobile Filter Toggle
                const mobileFilterBtn = document.getElementById('mobileFilterBtn');
                const sidebarFilter = document.getElementById('sidebarFilter');

                mobileFilterBtn.addEventListener('click', () => {
                    sidebarFilter.classList.toggle('hidden');
                    sidebarFilter.classList.toggle('flex');
                });
            });
        </script>
    @endpush
</x-app-layout>
