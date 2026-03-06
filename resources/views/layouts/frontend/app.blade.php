<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>IfnLink</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
    <link href="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />


    <!-- Favivon -->
    <link rel="icon" href="{{ asset('assets/images/irfan.png') }}" type="image/png">


    <!-- script -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Untuk Chrome, Safari, dan Edge */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Untuk Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="bg-gray-200 min-h-screen font-sans">

    <!-- Navbar -->
    @include('layouts.frontend.navbar')

    <main class="flex flex-col min-h-screen max-w-7xl mx-auto mt-2 px-4 sm:px-6 lg:px-8">
        @if (!Request::is('laporan-bank/rekap'))
            {{-- header --}}
            @include('layouts.frontend.header')
        @endif

        <!-- Main container -->
        @yield('container')
    </main>

    <!-- Footer -->
    @include('layouts.frontend.footer')


    <script>
        document.getElementById('hamburgerButton').onclick = function() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
