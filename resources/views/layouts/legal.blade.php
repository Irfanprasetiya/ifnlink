<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Legal') | Omzetly.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800">

    {{-- Header --}}
    <header class="bg-[#1d62fb] text-white sticky top-0 z-20">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="font-extrabold text-xl">Omzetly.id</a>
            <nav class="flex gap-4 text-sm">
                <a href="{{ route('kebijakan-privasi') }}" class="hover:text-blue-200">Kebijakan Privasi</a>
                <a href="{{ route('syarat-ketentuan') }}" class="hover:text-blue-200">Syarat Ketentuan</a>
            </nav>
        </div>
    </header>

    {{-- Content --}}
    <main class="max-w-4xl mx-auto px-4 py-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t bg-white py-6">
        <p class="text-center text-xs text-slate-400">
            © {{ date('Y') }} Omzetly.id. ALL RIGHTS RESERVED.
        </p>
    </footer>

</body>

</html>
