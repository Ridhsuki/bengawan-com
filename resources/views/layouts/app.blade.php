<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        ::selection {
            background-color: #f3d0f2;
            color: #333;
            border-radius: 4px;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.2);
            transition: background-color 0.2s ease-in-out;
        }
    </style>
</head>

<body class="bg-white text-gray-800 flex flex-col min-h-screen">
    @include('layouts.partials.header')
    @include('layouts.partials.navbar')

    <main>
        {{ $slot }}
    </main>

    @include('layouts.partials.footer')
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    @stack('scripts')
</body>

</html>
