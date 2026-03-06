<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favivon -->
    <link rel="icon" href="{{ asset('assets/images/irfan.png') }}" type="image/png">

    <!-- layouts.app -->
    <title>IfnLink - @yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{ sidebarOpen: false }" class="flex bg-gray-100 min-h-screen">
    {{-- Sidebar --}}
    @include('layouts.sidebar')

    {{-- Overlay untuk mobile --}}
    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden">
    </div>

    <div class="flex-1 flex flex-col">
        {{-- Navbar --}}
        @include('layouts.nav')

        {{-- Konten utama --}}
        <main :class="sidebarOpen ? 'md:ml-64' : 'md:ml-0'" class="transition-all duration-300 ease-in-out p-4 mt-14">
            <div class="container mx-auto px-4 py-6 space-y-6">
                <!-- Breadcrumb -->
                <x-admin.breadcrumb />
                @yield('container')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>
