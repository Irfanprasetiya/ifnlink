<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') !== null ? localStorage.getItem('sidebarOpen') === 'true' : true }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="BEdzi2zW-7tUjaCJNDfToBYMbPc2lxiUWRskPdnmXQc" />

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">
    <title>Omzetly.id - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ["Figtree", "Poppins", "ui-sans-serif", "system-ui"]
                    }
                }
            }
        }
    </script>


    <style>
        [x-cloak] {
            display: none !important;
        }

        aside[x-cloak] {
            display: none !important;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body x-data="{
    sidebarOpen: window.innerWidth >= 768 ? (localStorage.getItem('sidebarOpen') !== null ? localStorage.getItem('sidebarOpen') === 'true' : true) : false
}" x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val))" @resize.window="if (window.innerWidth < 768) sidebarOpen = false"
    class="bg-gray-100 min-h-screen">

    {{-- Overlay mobile --}}
    <div x-cloak x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden">
    </div>

    {{-- Navbar --}}
    @include('layouts.nav')

    <div class="flex pt-14">

        {{-- Sidebar --}}
        <aside x-cloak :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-14 left-0 bottom-0 w-64 bg-gray-800 z-40">
            @include('layouts.sidebar')
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 w-full min-w-0 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">
            <main class="p-3 sm:p-4 w-full max-w-full overflow-x-hidden">
                @yield('container')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
