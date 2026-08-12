<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Omzetly.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    <div class="text-center max-w-md">
        {{-- Ikon --}}
        <div class="text-8xl mb-6">🔒</div>

        {{-- Angka 403 --}}
        <h1 class="text-7xl font-extrabold text-slate-900 mb-2">403</h1>

        {{-- Judul --}}
        <h2 class="text-2xl font-bold text-slate-800 mb-3">Akses Ditolak</h2>

        {{-- Pesan --}}
        <p class="text-slate-500 mb-8">
            {{ $exception->getMessage() ?: 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.' }}
        </p>

        {{-- Tombol --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-primary px-6 py-3 text-sm font-semibold text-white shadow-elegant hover:-translate-y-0.5 transition"
                    style="background: linear-gradient(135deg, #1d4ed8, #3b82f6);">
                    ← Kembali ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-primary px-6 py-3 text-sm font-semibold text-white shadow-elegant hover:-translate-y-0.5 transition"
                    style="background: linear-gradient(135deg, #1d4ed8, #3b82f6);">
                    🔑 Login
                </a>
            @endauth

            <a href="{{ url('/') }}"
                class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                🏠 Beranda
            </a>
        </div>

        {{-- Footer --}}
        <p class="mt-10 text-xs text-slate-400">
            © {{ date('Y') }} Omzetly.id — Butuh bantuan?
            <a href="#" class="text-blue-600 hover:underline">Hubungi Support</a>
        </p>
    </div>
</body>

</html>
