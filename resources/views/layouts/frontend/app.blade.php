<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Omzetly.id</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Gunakan 1 versi Flowbite terbaru saja agar tidak bentrok -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Icon --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">

    <!-- Script -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
            /* Menghilangkan kotak biru kaku saat tombol/link ditekan di HP */
            -webkit-tap-highlight-color: transparent;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<!-- Mengubah bg-gray-200 menjadi bg-slate-50 agar lebih modern dan tidak terlalu kontras -->

<body class="bg-slate-50 min-h-screen text-slate-800 antialiased">

    <!-- Navbar -->
    @include('layouts.frontend.navbar')

    <!-- Padding diperlebar di HP (px-3) agar konten tidak sempit -->
    <main
        class="flex flex-col min-h-screen w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8  
        {{ Request::routeIs('main') || Request::is('laporan-bank/rekap') ? 'mt-3' : 'mt-20 sm:mt-24' }}">

        {{-- Header hanya di halaman main/home --}}
        @if (Request::routeIs('main'))
            @include('layouts.frontend.header')
        @endif

        <!-- Main container -->
        @yield('container')

    </main>

    <!-- Footer -->
    @include('layouts.frontend.footer')

    <script>
        const hamburgerBtn = document.getElementById('hamburgerButton');
        if (hamburgerBtn) {
            hamburgerBtn.addEventListener('click', function() {
                const mobileMenu = document.getElementById('mobileMenu');
                if (mobileMenu) {
                    mobileMenu.classList.toggle('hidden');
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
