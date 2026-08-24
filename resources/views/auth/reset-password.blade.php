<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Omzetly.id</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/png">

    @if (app()->isProduction())
        <script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{sans:["Figtree","Poppins","ui-sans-serif","system-ui"]}}}}</script>
    @else
        <script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{sans:["Figtree","Poppins","ui-sans-serif","system-ui"]}}}}</script>
    @endif
</head>

<body class="font-[Poppins] bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-900 dark:to-gray-800">

    <section class="flex flex-col items-center justify-center min-h-screen px-6 py-10">

        <div
            class="w-full max-w-md bg-white/80 backdrop-blur-md rounded-2xl shadow-lg dark:bg-gray-800/80 p-8 border border-white/20">
            <div class="flex flex-col items-center mb-8">
                <img class="h-24" src="{{ asset('assets/images/logo/favicon.png') }}" alt="logo">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reset Password</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Buat password baru untuk akun Anda</p>
            </div>

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

            {{-- ✅ Info Tenant --}}
            @if ($namaToko)
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-xs text-blue-600 font-bold uppercase mb-1">Reset Password Untuk</p>
                    <p class="text-sm font-bold text-gray-800">{{ $namaToko }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="username" value="{{ $username }}">

                <div>
                    <label for="username" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Username
                    </label>
                    <input type="text" name="username_display" id="username_display" 
                        value="{{ $username ?? old('username') }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-300 outline-none transition cursor-not-allowed"
                        readonly>
                </div>

                {{-- ✅ Email Tenant (Readonly) --}}
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Pemilik Toko
                    </label>
                    <input type="email" name="email_display" id="email_display" 
                        value="{{ $email ?? 'Email tidak tersedia' }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-300 outline-none transition cursor-not-allowed"
                        readonly>
                    <p class="text-xs text-gray-400 mt-1">
                        * Email ini digunakan untuk verifikasi dan tidak dapat diubah
                    </p>
                </div>

                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password Baru
                    </label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Masukkan password baru" required>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Konfirmasi Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Ulangi password baru" required>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <button type="submit"
                    class="w-full py-3 text-white font-semibold bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition transform active:scale-[0.98] focus:ring-4 focus:ring-blue-300 focus:outline-none">
                    Reset Password
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