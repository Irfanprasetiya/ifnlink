<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Legal') | Omzetly.id</title>

    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ asset('assets/images/logo/omzetly.png') }}" type="image/png">

    <!-- Gunakan Font Inter agar seragam dengan Landing Page -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#2563eb',
                            glow: '#60a5fa'
                        },
                    },
                    boxShadow: {
                        soft: '0 10px 30px -10px rgba(15,23,42,0.06)',
                    }
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800 flex flex-col selection:bg-blue-600 selection:text-white">

    {{-- ========== NAVBAR ========== --}}
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200/80 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 sm:h-20 flex items-center justify-between">

            <!-- Logo Brand -->
            <a href="/" class="flex items-center gap-2 sm:gap-2.5 group transition-transform active:scale-95">
                <div
                    class="h-8 w-8 sm:h-10 sm:w-10 rounded-lg sm:rounded-xl overflow-hidden shadow-sm border border-slate-100 flex items-center justify-center bg-white">
                    <img src="{{ asset('assets/images/logo/omzetly.png') }}" alt="Omzetly"
                        class="h-full w-full object-contain">
                </div>
                <span class="text-lg sm:text-xl font-extrabold tracking-tight text-slate-900">
                    Omzetly<span class="text-blue-600">.id</span>
                </span>
            </a>

            <!-- Navigasi Tengah (Desktop) -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-bold">
                <a href="{{ route('kebijakan-privasi') }}"
                    class="transition-colors hover:text-blue-600 {{ request()->routeIs('kebijakan-privasi') ? 'text-blue-600' : 'text-slate-500' }}">
                    Kebijakan Privasi
                </a>
                <a href="{{ route('syarat-ketentuan') }}"
                    class="transition-colors hover:text-blue-600 {{ request()->routeIs('syarat-ketentuan') ? 'text-blue-600' : 'text-slate-500' }}">
                    Syarat & Ketentuan
                </a>
            </nav>

            <!-- Aksi Kanan -->
            <div>
                <a href="/"
                    class="hidden sm:inline-flex items-center justify-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2 text-xs sm:text-sm font-bold text-slate-700 transition-colors active:scale-95">
                    Kembali ke Beranda
                </a>

                <!-- Menu Mobile Dropdown (Simple) -->
                <div class="md:hidden flex gap-3 text-[11px] font-bold text-slate-500">
                    <a href="{{ route('kebijakan-privasi') }}"
                        class="{{ request()->routeIs('kebijakan-privasi') ? 'text-blue-600' : '' }}">Privasi</a>
                    <span>•</span>
                    <a href="{{ route('syarat-ketentuan') }}"
                        class="{{ request()->routeIs('syarat-ketentuan') ? 'text-blue-600' : '' }}">Syarat</a>
                </div>
            </div>
        </div>
    </header>

    {{-- ========== KONTEN UTAMA ========== --}}
    <main class="flex-1 w-full relative">
        <!-- Latar Belakang Aksen Tipis -->
        <div
            class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-blue-50/50 to-transparent -z-10 pointer-events-none">
        </div>

        @yield('content')
    </main>

    {{-- ========== FOOTER ========== --}}
    <footer class="border-t border-slate-200/80 bg-white py-8 mt-auto">
        <div class="max-w-4xl mx-auto px-4 flex flex-col items-center justify-center gap-4">
            <div class="flex items-center gap-2 opacity-50 grayscale">
                <img src="{{ asset('assets/images/logo/omzetly.png') }}" alt="Omzetly" class="h-5 w-5 object-contain">
                <span class="font-extrabold text-sm tracking-tight text-slate-900">Omzetly.id</span>
            </div>
            <p class="text-center text-xs font-medium text-slate-400">
                &copy; {{ date('Y') }} Omzetly.id. Seluruh hak cipta dilindungi undang-undang.<br>
                Sistem Manajemen Agen Digital Berbasis Cloud.
            </p>
        </div>
    </footer>

</body>

</html>
