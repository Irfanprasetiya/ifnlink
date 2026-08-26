<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
    <title>Omzetly.id — Manajemen Transaksi Agen Digital</title>
    <meta name="description"
        content="Platform cloud untuk owner agen digital: catat transaksi, hitung laba otomatis, dan pantau performa cabang dari mana saja.">
    <meta name="google-site-verification" content="BEdzi2zW-7tUjaCJNDfToBYMbPc2lxiUWRskPdnmXQc" />

    {{-- Alpine.js - WAJIB untuk dropdown --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ["Figtree", "Poppins", "ui-sans-serif", "system-ui"],
                    }
                }
            }
        }
    </script>

    {{-- Flowbite --}}
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Icon --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">

    <style>
        body {
            font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif;
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

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800 antialiased">

    @include('layouts.frontend.navbar')

    <main
        class="flex flex-col min-h-screen w-full max-w-7xl mx-auto px-3 sm:px-6 lg:px-8
        {{ Request::routeIs('main') || Request::is('laporan-bank/rekap') ? 'mt-3' : 'mt-20 sm:mt-24' }}">

        @if (Request::routeIs('main'))
            @include('layouts.frontend.header')
        @endif

        @yield('container')

    </main>

    @include('layouts.frontend.footer')

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>
