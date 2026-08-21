<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Omzetly.id</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/png">

    @if (app()->isProduction())
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body
    class="font-[Poppins] bg-slate-50 dark:bg-gray-900 min-h-screen relative overflow-x-hidden selection:bg-blue-500 selection:text-white">

    {{-- Background Ornaments --}}
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-400/20 dark:bg-blue-900/20 blur-[100px]">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-400/20 dark:bg-indigo-900/20 blur-[100px]">
        </div>
    </div>

    <section class="flex flex-col items-center justify-center min-h-screen px-4 sm:px-6 py-10 relative z-10">

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div
            class="w-full max-w-md bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-8 border border-white/50 dark:border-gray-700/50 animate-in fade-in zoom-in-95 duration-500">

            {{-- Header Logo --}}
            <div class="flex flex-col items-center mb-8 sm:mb-10 text-center">
                <div class="relative group cursor-pointer mb-4">
                    <div
                        class="absolute inset-0 bg-blue-100 dark:bg-blue-900/50 rounded-full blur-xl opacity-50 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <img class="h-24 sm:h-28 relative z-10 drop-shadow-lg transition-transform duration-300 group-hover:scale-105"
                        src="{{ asset('assets/images/logo/favicon.png') }}" alt="Logo Omzetly">
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Omzetly.id
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base mt-1.5 font-medium">Selamat datang
                    kembali, silakan masuk.</p>
            </div>

            {{-- Alerts & Notifications --}}
            <div class="space-y-3 mb-6">
                @if (session('error'))
                    <div
                        class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[11px] sm:text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @if (session('info'))
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400 px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-[11px] sm:text-sm font-medium">{{ session('info') }}</span>
                    </div>
                @endif

                @if (request('message') === 'suspended')
                    <div
                        class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400 px-4 py-3.5 rounded-2xl flex items-center gap-3 shadow-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                        <span class="text-[11px] sm:text-sm font-medium">Akun atau Toko Anda sedang dinonaktifkan
                            sementara.</span>
                    </div>
                @endif
            </div>

            {{-- Form Login --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username"
                        class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        {{-- Class 'text-base sm:text-sm' mencegah auto-zoom di iOS --}}
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            autofocus autocomplete="username"
                            class="w-full pl-11 pr-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all"
                            placeholder="Masukkan username Anda">
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                </div>

                <div>
                    <label for="password"
                        class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2">
                                </path>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                            class="w-full pl-11 pr-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all"
                            placeholder="Masukkan password Anda">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Masuk ke Dasbor
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 dark:border-gray-700/50 pt-6">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">
                    Belum punya akun?
                    <a href="{{ route('agen.register') }}"
                        class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors ml-1">
                        Daftar sekarang
                    </a>
                </p>
            </div>

            <div class="mt-4 flex justify-center items-center">
                <a href="/"
                    class="inline-flex items-center text-xs sm:text-sm font-semibold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors bg-gray-50 dark:bg-gray-800 px-4 py-2 rounded-lg border border-gray-100 dark:border-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <p class="text-center text-[10px] sm:text-[11px] text-gray-400 mt-8 tracking-wide font-medium">
                © {{ date('Y') }} Omzetly.id. ALL RIGHTS RESERVED.
            </p>
        </div>
    </section>
</body>

</html>
