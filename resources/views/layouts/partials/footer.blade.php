<footer class="bg-footer-bg pt-12 pb-6 mt-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">

            <div>
                <div class="flex items-center md:text-2xl mb-4">
                    <img src="{{ asset('assets/img/logo2.png') }}"
                        alt="{{ $settings->company_name ?? 'Bengawan Computer' }}" loading="lazy">
                </div>

                <div class="text-sm text-gray-700 leading-relaxed mb-4 space-y-1">
                    <div class="mb-2">
                        {!! nl2br(e($settings->address ?? '-')) !!}
                    </div>

                    <div>
                        Telp(WA) :
                        <a href="{{ $settings->getWhatsappUrl('Halo Bengawan Computer.') }}" target="_blank"
                            class="hover:text-blue-600 hover:underline">
                            {{ $settings->phone ?? '-' }}
                        </a>
                    </div>

                    @foreach ($settings->social_media_list as $social)
                        <div>
                            {{ $social['platform'] }} :
                            <a href="{{ $social['url'] }}" target="_blank" class="hover:text-blue-600 hover:underline">
                                {{ $social['username'] }}
                            </a>
                        </div>
                    @endforeach
                </div>
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

            <div id="feedback-form">
                <h4 class="font-bold text-gray-800 mb-2">Beri Masukan</h4>
                <p class="text-xs text-gray-600 mb-3">Bantu kami jadi lebih baik! Sampaikan kritik dan saran Anda di
                    sini</p>

                @if (session('success'))
                    <div
                        class="mb-3 p-3 bg-green-50 border border-green-200 text-green-700 text-xs rounded-lg shadow-sm flex items-start animate-fade-in-up">
                        <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->has('limit'))
                    <div class="mb-3 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg shadow-sm flex items-start animate-pulse"
                        role="alert">
                        <svg class="w-4 h-4 mr-1.5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="font-bold">Limit Reached!</span>
                            To prevent duplicate submissions, please try again in
                            <span id="retry-countdown" class="font-bold font-mono text-red-800 text-sm">
                                {{ session('retry_seconds', 60) }}
                            </span>
                            seconds.
                        </div>
                    </div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {var countdownElement = document.getElementById('retry-countdown');var seconds = parseInt(countdownElement.innerText);var timer = setInterval(function(){seconds--;if (seconds <= 0){clearInterval(timer);countdownElement.innerText = "0";countdownElement.parentElement.innerHTML = "You can send your message now.";} else {countdownElement.innerText = seconds;}}, 1000);});
                    </script>
                @endif

                @if ($errors->has('message'))
                    <div class="mb-3 p-2 text-red-600 text-xs">
                        {{ $errors->first('message') }}
                    </div>
                @endif

                <form action="{{ route('feedback.store') }}" method="POST"
                    class="flex items-center border border-gray-400 rounded-full bg-transparent p-1 focus-within:ring-2 focus-within:ring-brand-blue focus-within:border-transparent transition relative">
                    @csrf

                    <input type="text" name="message" value="{{ old('message') }}"
                        placeholder="Tulis masukan kalian disini" required autocomplete="off"
                        class="bg-transparent flex-grow px-4 py-1 text-sm outline-none text-gray-700 placeholder-gray-400 w-full">

                    <button type="submit"
                        class="bg-white text-brand-blue text-sm font-bold px-6 py-1.5 rounded-full shadow hover:bg-gray-50 transition active:scale-95 whitespace-nowrap cursor-pointer">
                        Kirim
                    </button>
                </form>
            </div>

        </div>

        <div class="border-t border-gray-300 pt-6 text-center text-xs text-gray-600">
            &copy; {{ date('Y') }} {{ $settings->company_name ?? 'Bengawan Komputer' }}
        </div>
    </div>
</footer>
