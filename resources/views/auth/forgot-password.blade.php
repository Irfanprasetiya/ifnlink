<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | Omzetly.id</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/png">

    @if (app()->isProduction())
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="font-[Poppins] bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-900 dark:to-gray-800">

    <section class="flex flex-col items-center justify-center min-h-screen px-6 py-10">

        <div
            class="w-full max-w-md bg-white/80 backdrop-blur-md rounded-2xl shadow-lg dark:bg-gray-800/80 p-8 border border-white/20">
            <div class="flex flex-col items-center mb-8">
                <img class="h-24" src="{{ asset('assets/images/logo/favicon.png') }}" alt="logo">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lupa Password</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Masukkan username atau email untuk reset
                    password</p>
            </div>

            @if (session('status'))
                <div
                    class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if (session('reset_link'))
                <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg text-sm">
                    <p class="font-bold mb-2">🔧 Development Mode - Link Reset:</p>
                    @if (session('user_info'))
                        <p class="text-xs text-yellow-700 mb-2">{{ session('user_info') }}</p>
                    @endif
                    <a href="{{ session('reset_link') }}" class="text-blue-600 underline break-all text-xs font-mono">
                        {{ session('reset_link') }}
                    </a>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="login" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Username atau Email
                    </label>
                    <input type="text" name="login" id="login" value="{{ old('login') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Masukkan username atau email" required autofocus>
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                    <p class="text-xs text-gray-400 mt-1">
                        * Email yang digunakan adalah email pemilik toko
                    </p>
                </div>

                <button type="submit"
                    class="w-full py-3 text-white font-semibold bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition transform active:scale-[0.98] focus:ring-4 focus:ring-blue-300 focus:outline-none">
                    Kirim Link Reset
                </button>
            </form>

            <div class="mt-6 flex justify-center items-center">
                <a href="{{ route('login') }}"
                    class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Login
                </a>
            </div>

            <p class="text-center text-[11px] text-gray-400 dark:text-gray-500 mt-6 tracking-wide">
                © {{ date('Y') }} Omzetly.id. ALL RIGHTS RESERVED.
            </p>
        </div>
    </section>
</body>

</html>
