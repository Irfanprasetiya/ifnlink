<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Sedang Pemeliharaan</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-800">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl border border-slate-100 p-8 text-center">

            {{-- Icon Ilustrasi / Status --}}
            <div
                class="mx-auto flex items-center justify-center w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 mb-6 border border-amber-100 shadow-sm">
                <svg class="w-8 h-8 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>

            {{-- Informasi Error --}}
            <span
                class="inline-flex items-center px-3 py-1 text-xs font-bold rounded-full bg-amber-50 text-amber-700 border border-amber-200/60 mb-3">
                Error 503 — Maintenance
            </span>

            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mb-2">
                Sistem Sedang Pemeliharaan
            </h1>

            <p class="text-xs sm:text-sm text-slate-500 font-medium mb-8 leading-relaxed">
                Mohon maaf atas ketidaknyamanannya. Kami sedang melakukan peningkatan sistem untuk menghadirkan layanan
                yang lebih baik. Silakan coba beberapa saat lagi.
            </p>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button onclick="window.location.reload();"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Muat Ulang Halaman
                </button>
            </div>

        </div>
    </div>
</body>

</html>
