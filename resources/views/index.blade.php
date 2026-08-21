<!doctype html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Omzetly.id — Manajemen Transaksi Agen Digital Berbasis Cloud</title>
    <meta name="description"
        content="Platform cloud untuk owner agen digital: catat transaksi, hitung laba otomatis, dan pantau performa cabang dari mana saja." />
    {{-- <link rel="icon" href="{{ asset('assets/images/') }}" type="image/svg+xml"> --}}
    <link rel="manifest" href="{{ asset('assets/images/favicon.png') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        ink: '#0f172a',
                        'ink-soft': '#475569',
                        surface: '#f8fafc',
                        brand: {
                            DEFAULT: '#2563eb',
                            glow: '#60a5fa'
                        },
                    },
                    boxShadow: {
                        elegant: '0 25px 50px -12px rgba(37,99,235,0.15)',
                        soft: '0 10px 30px -10px rgba(15,23,42,0.06)',
                    },
                    backgroundImage: {
                        'gradient-primary': 'linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%)',
                    },
                },
            },
        };
    </script>
    <style>
        html,
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background-color: #ffffff;
            color: #0f172a;
        }

        .text-gradient {
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        details>summary {
            list-style: none;
        }

        details>summary::-webkit-details-marker {
            display: none;
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-white text-slate-900 antialiased selection:bg-blue-600 selection:text-white">
    <!-- NAV -->
    <header id="nav" class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
        <div
            class="mx-auto mt-3 flex h-20 max-w-7xl items-center justify-between rounded-2xl border border-transparent px-4 sm:mt-4 sm:px-6 lg:px-8">
            <a href="#beranda" class="flex items-center gap-2.5 group">
                <div
                    class="h-10 w-10 md:h-12 md:w-12 rounded-xl overflow-hidden shadow-md transition group-hover:scale-105 flex items-center justify-center bg-white">
                    <img src="{{ asset('assets/images/logo/omzetly.png') }}" alt="Omzetly"
                        class="h-full w-full object-contain">
                </div>
                <span class="text-xl md:text-2xl font-extrabold tracking-tight">Omzetly<span
                        class="text-gradient">.id</span></span>
            </a>

            <!-- Menu Desktop: text-sm diubah menjadi text-base (16px) -->
            <nav class="hidden items-center gap-6 text-sm font-medium text-slate-600 md:flex lg:gap-8">
                <a href="#beranda" class="hover:text-brand transition">Beranda</a>
                <a href="#fitur" class="hover:text-brand transition">Fitur Utama</a>
                <a href="#tampilan" class="hover:text-brand transition">Tampilan</a>
                <a href="#testimoni" class="hover:text-brand transition">Testimoni</a>
                <a href="#harga" class="hover:text-brand transition">Harga</a>
            </nav>

            <div class="hidden items-center gap-3 md:flex">
                <!-- Tombol Desktop: text-sm diubah menjadi text-base -->
                <a href="{{ route('login') }}"
                    class="rounded-xl px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 hover:text-brand">Masuk</a>
                <a href="{{ route('agen.register') }}"
                    class="rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-soft transition hover:-translate-y-0.5 hover:bg-slate-800">
                    Coba Gratis
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="menuBtn"
                class="rounded-2xl border border-slate-200 bg-white p-2.5 text-slate-700 shadow-soft focus:outline-none md:hidden">
                <svg id="icon-menu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
                <svg id="icon-close" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Mobile Nav Menu -->
        <div id="mobileMenu"
            class="absolute inset-x-4 top-24 hidden rounded-2xl border border-slate-200 bg-white/95 shadow-[0_30px_80px_-30px_rgba(15,23,42,0.28)] backdrop-blur-xl md:hidden sm:inset-x-6">
            <!-- Menu Mobile: text-base diubah menjadi text-lg (18px) -->
            <div class="flex flex-col gap-2 px-5 py-5 text-base font-semibold">
                <a href="#beranda"
                    class="rounded-xl border border-transparent px-4 py-3 text-slate-700 transition hover:border-slate-200 hover:bg-slate-50">Beranda</a>
                <a href="#fitur"
                    class="rounded-xl border border-transparent px-4 py-3 text-slate-700 transition hover:border-slate-200 hover:bg-slate-50">Fitur
                    Utama</a>
                <a href="#tampilan"
                    class="rounded-xl border border-transparent px-4 py-3 text-slate-700 transition hover:border-slate-200 hover:bg-slate-50">Tampilan
                    Aplikasi</a>
                <a href="#testimoni"
                    class="rounded-xl border border-transparent px-4 py-3 text-slate-700 transition hover:border-slate-200 hover:bg-slate-50">Testimoni</a>
                <a href="#harga"
                    class="rounded-xl border border-transparent px-4 py-3 text-slate-700 transition hover:border-slate-200 hover:bg-slate-50">Harga</a>

                <div class="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4">
                    <a href="{{ route('login') }}"
                        class="rounded-2xl bg-blue-50 px-4 py-3 text-center text-sm font-bold text-brand">Masuk Akun</a>
                    <a href="{{ route('agen.register') }}"
                        class="rounded-2xl bg-slate-900 py-3 text-center text-sm font-bold text-white shadow-soft">Coba
                        Gratis
                        Sekarang</a>
                </div>
            </div>
        </div>
    </header>

    <!-- HERO (ASIMETRIS LAYOUT) -->
    <section id="beranda"
        class="relative overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(96,165,250,0.16),_transparent_34%),linear-gradient(to_bottom,_rgba(248,250,252,0.95),_#ffffff)] pt-32 pb-20 md:pt-40 md:pb-28 lg:pt-48 lg:pb-36">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-14 lg:grid-cols-12 lg:gap-16">

                <!-- KIRI: Teks & CTA -->
                <div class="lg:col-span-7">
                    <div
                        class="mb-6 inline-flex items-center gap-2 rounded-full border border-blue-200/70 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-700 shadow-soft">
                        <span class="h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                        Sistem Cloud Manajemen Agen No. 1
                    </div>

                    <h1
                        class="min-h-[104px] max-w-3xl text-4xl font-extrabold tracking-tight text-slate-950 leading-tight md:min-h-[128px] md:text-5xl lg:min-h-[150px] lg:text-6xl">
                        Pusat Kendali <br />
                        {{-- Elemen untuk teks dinamis & kursor --}}
                        <span class="text-gradient" id="typed-text"></span><span
                            class="text-blue-600 animate-[blink_1s_step-end_infinite]">|</span>
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-8 text-slate-600 md:text-lg">
                        Tinggalkan catatan manual. Pantau mutasi bank, hitung laba bersih otomatis, dan kelola banyak
                        cabang agen dalam satu platform cloud yang aman.
                    </p>

                    <div class="mt-10 flex flex-col items-stretch gap-4 sm:flex-row sm:items-center">
                        <a href="#harga"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-primary px-8 py-4 text-sm font-bold text-white shadow-[0_24px_60px_-24px_rgba(37,99,235,0.7)] transition-all hover:-translate-y-0.5 hover:opacity-95 md:text-base">
                            Mulai Gratis 14 Hari →
                        </a>
                        <a href="#fitur"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white px-8 py-4 text-sm font-bold text-slate-700 shadow-soft transition-all hover:-translate-y-0.5 hover:bg-slate-50 md:text-base">
                            Eksplor Fitur
                        </a>
                    </div>

                    <p class="mt-6 text-xs font-medium leading-relaxed text-slate-500 md:text-sm">
                        ✨ Tanpa kartu kredit &nbsp;·&nbsp; 🚀 Aktif dalam 2 menit &nbsp;·&nbsp; 🔒 Data terisolasi aman
                    </p>
                </div>

                <!-- KANAN: Floating Card Dashboard Minimalist -->
                <div class="hidden sm:block lg:col-span-5">
                    <div class="relative">
                        <div class="absolute -inset-5 rounded-[2rem] bg-blue-600/12 blur-3xl"></div>
                        <div
                            class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-elegant md:p-8">
                            <div class="absolute inset-x-0 top-0 h-24 bg-gradient-to-b from-blue-50/70 to-transparent">
                            </div>
                            <div class="relative flex items-center justify-between border-b border-slate-100 pb-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-blue-600 text-white font-bold grid place-items-center">
                                        O</div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-900">Omzetly Core</div>
                                        <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1">●
                                            Live Sync</div>
                                    </div>
                                </div>
                                <span
                                    class="text-xs bg-slate-100 text-slate-600 px-3 py-1 rounded-full font-bold">Cabang
                                    Utama</span>
                            </div>

                            <div class="relative mt-6 space-y-4">
                                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 shadow-soft">
                                    <span class="text-xs text-slate-500 font-medium">Saldo Rekening BRI Pusat</span>
                                    <div class="text-2xl font-extrabold text-slate-900 mt-1">Rp 45.280.000</div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-soft">
                                        <span class="text-xs text-slate-500 font-medium">Transaksi Hari Ini</span>
                                        <div class="text-lg font-bold text-slate-900 mt-1">142 Tx</div>
                                    </div>
                                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 shadow-soft">
                                        <span class="text-xs text-slate-500 font-medium">Estimasi Laba</span>
                                        <div class="text-lg font-bold text-emerald-600 mt-1">+Rp 850rb</div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-6 flex items-center justify-between border-t border-slate-100 pt-4 text-xs font-medium text-slate-500">
                                <span>Perbarui terakhir: Barusan</span>
                                <span class="text-blue-600 font-bold">Sistem Stabil</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- STATS (MINIMALIST BAR) -->
    <section class="bg-slate-950 py-14 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-2 gap-8 rounded-[2rem] border border-white/10 bg-white/5 px-6 py-8 text-center backdrop-blur-sm md:grid-cols-4 md:gap-4 md:px-10">
                <div class="space-y-2">
                    <div class="text-2xl md:text-3xl font-extrabold text-blue-400">1.200+</div>
                    <div class="text-[10px] md:text-xs text-slate-400 uppercase tracking-widest mt-1.5 font-semibold">
                        Agen Aktif</div>
                </div>
                <div class="space-y-2">
                    <div class="text-2xl md:text-3xl font-extrabold text-blue-400">Rp 48M+</div>
                    <div class="text-[10px] md:text-xs text-slate-400 uppercase tracking-widest mt-1.5 font-semibold">
                        Volume Transaksi</div>
                </div>
                <div class="space-y-2">
                    <div class="text-2xl md:text-3xl font-extrabold text-blue-400">99.9%</div>
                    <div class="text-[10px] md:text-xs text-slate-400 uppercase tracking-widest mt-1.5 font-semibold">
                        Server Uptime</div>
                </div>
                <div class="space-y-2">
                    <div class="text-2xl md:text-3xl font-extrabold text-blue-400">4.9/5</div>
                    <div class="text-[10px] md:text-xs text-slate-400 uppercase tracking-widest mt-1.5 font-semibold">
                        Rating Pengguna</div>
                </div>
            </div>
        </div>
    </section>

    <!-- SHOWCASE APLIKASI (SCREENSHOTS DENGAN TAB INTERAKTIF) -->
    <section id="tampilan" class="overflow-hidden border-b border-slate-200 bg-slate-50 py-24 md:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <span
                class="rounded-full border border-blue-200 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600 shadow-soft">Antarmuka
                Sistem</span>
            <h2 class="mt-6 text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl lg:text-5xl">
                Desain Bersih. Mudah Dipahami.
            </h2>
            <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-slate-600 md:text-lg">
                Tampilan antarmuka yang modern dan intuitif. Dirancang khusus agar owner dan operator agen Anda bisa
                langsung
                paham penggunaannya dalam hitungan menit.
            </p>

            <!-- Navigasi Tab Showcase -->
            <div
                class="no-scrollbar mt-12 flex items-center justify-start gap-3 overflow-x-auto pb-4 md:justify-center">
                <button onclick="switchTab('admin')" id="btn-admin"
                    class="tab-btn shrink-0 rounded-full border border-transparent bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-soft transition-all">
                    Pusat Kendali (Admin)
                </button>
                <button onclick="switchTab('user')" id="btn-user"
                    class="tab-btn shrink-0 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 shadow-soft transition-all hover:bg-slate-100 hover:text-slate-600">
                    Dashboard Kasir (User)
                </button>
                <button onclick="switchTab('laba')" id="btn-laba"
                    class="tab-btn shrink-0 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 shadow-soft transition-all hover:bg-slate-100 hover:text-slate-600">
                    Laporan Laba Rugi
                </button>
                <button onclick="switchTab('transaksi')" id="btn-transaksi"
                    class="tab-btn shrink-0 rounded-full border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600 shadow-soft transition-all hover:bg-slate-100 hover:text-slate-600">
                    Input Transaksi
                </button>
            </div>

            <!-- Kontainer Gambar -->
            <div class="relative mx-auto mt-10 max-w-6xl">
                <!-- Efek shadow/glow di belakang gambar -->
                <div
                    class="absolute -inset-1 rounded-[2rem] bg-gradient-to-r from-blue-500/20 to-indigo-500/20 blur-3xl md:-inset-3">
                </div>

                <!-- 1. Screenshot Dashboard Admin -->
                <!-- TODO: Ganti src dengan path file "Screenshot 2026-08-06 005032.png" -->
                <div
                    class="tab-panel relative block overflow-hidden rounded-[1.75rem] border border-slate-200/60 bg-white p-2 shadow-[0_24px_60px_-28px_rgba(15,23,42,0.22)] md:p-2.5">
                    <img id="img-admin" src="{{ asset('assets/images/dashAdmin.png') }}"
                        alt="Tampilan Dashboard Admin Omzetly"
                        class="tab-img aspect-[16/10] w-full rounded-[1.35rem] bg-slate-50 object-contain object-top shadow-soft transition-opacity duration-500">
                </div>

                <!-- 2. Screenshot Dashboard User -->
                <!-- TODO: Ganti src dengan screenshot dashboard saat login sebagai operator cabang -->
                <div
                    class="tab-panel relative hidden overflow-hidden rounded-[1.75rem] border border-slate-200/60 bg-white p-2 shadow-[0_24px_60px_-28px_rgba(15,23,42,0.22)] md:p-2.5">
                    <img id="img-user" src="{{ asset('assets/images/dashUser.png') }}"
                        alt="Tampilan Dashboard User Omzetly"
                        class="tab-img aspect-[16/10] w-full rounded-[1.35rem] bg-slate-50 object-contain object-top shadow-soft transition-opacity duration-500">
                </div>

                <!-- 3. Screenshot Laba Rugi -->
                <!-- TODO: Ganti src dengan screenshot tabel laporan laba rugi yang sudah kita perbaiki desainnya -->
                <div
                    class="tab-panel relative hidden overflow-hidden rounded-[1.75rem] border border-slate-200/60 bg-white p-2 shadow-[0_24px_60px_-28px_rgba(15,23,42,0.22)] md:p-2.5">
                    <img id="img-laba" src="{{ asset('assets/images/labaRugi.png') }}"
                        alt="Tampilan Laporan Laba Rugi Omzetly"
                        class="tab-img aspect-[16/10] w-full rounded-[1.35rem] bg-slate-50 object-contain object-top shadow-soft transition-opacity duration-500">
                </div>

                <!-- 4. Screenshot Input Transaksi -->
                <!-- TODO: Ganti src dengan form tempat operator memasukkan nominal transaksi/tarik tunai -->
                <div
                    class="tab-panel relative hidden overflow-hidden rounded-[1.75rem] border border-slate-200/60 bg-white p-2 shadow-[0_24px_60px_-28px_rgba(15,23,42,0.22)] md:p-2.5">
                    <img id="img-transaksi" src="{{ asset('assets/images/trxser.png') }}"
                        alt="Tampilan Input Transaksi Omzetly"
                        class="tab-img aspect-[16/10] w-full rounded-[1.35rem] bg-slate-50 object-contain object-top shadow-soft transition-opacity duration-500">
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES (BENTO GRID LAYOUT) -->
    <section id="fitur" class="bg-slate-50/60 py-24 md:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto mb-14 max-w-2xl text-center md:mb-16">
                <span
                    class="inline-block rounded-full border border-blue-200 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600 shadow-soft">Arsitektur
                    Modern</span>
                <h2 class="mt-6 text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl lg:text-5xl">
                    Dirancang Untuk Kecepatan Operasional.
                </h2>
                <p class="mx-auto mt-5 max-w-xl text-base leading-8 text-slate-600 md:text-lg">
                    Fitur dirancang secara spesifik berdasarkan alur kerja nyata di lapangan agen BRILink & retail,
                    bukan teori semata.
                </p>
            </div>

            <!-- Bento Grid -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                <!-- Box Besar 1 (Span 2) -->
                <div
                    class="relative flex flex-col justify-between overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white p-8 shadow-soft transition hover:-translate-y-1 hover:shadow-[0_28px_70px_-35px_rgba(15,23,42,0.3)] md:col-span-2 md:p-10">
                    <div
                        class="absolute right-0 top-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl pointer-events-none -mr-20 -mt-20">
                    </div>
                    <div class="relative z-10">
                        <span
                            class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-1.5 text-[11px] font-bold text-blue-600 md:text-xs">Multi-Tenancy
                            & Cabang</span>
                        <h3 class="mt-5 text-2xl font-bold leading-tight text-slate-900 md:text-3xl">Kelola Banyak
                            Cabang Dalam Satu Akun Utama
                        </h3>
                        <p class="mt-4 max-w-lg text-base leading-8 text-slate-600">Setiap cabang memiliki data
                            terisolasi. Owner dapat
                            memantau kas masuk dan keluar secara terpisah maupun gabungan secara instan.</p>
                    </div>
                    <div
                        class="relative z-10 mt-8 flex flex-wrap items-center gap-5 border-t border-slate-100 pt-6 text-sm font-semibold text-slate-700">
                        <span class="flex items-center gap-1.5"><span class="text-blue-600">✓</span> Cabang Tak
                            Terbatas</span>
                        <span class="flex items-center gap-1.5"><span class="text-blue-600">✓</span> Kontrol Akses
                            Operator</span>
                    </div>
                </div>

                <!-- Box Kecil 2 -->
                <div
                    class="flex flex-col justify-between rounded-[2rem] border border-slate-200/80 bg-white p-8 shadow-soft transition hover:-translate-y-1 hover:shadow-[0_28px_70px_-35px_rgba(15,23,42,0.3)]">
                    <div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-6 font-bold text-2xl">
                            ⚡</div>
                        <h3 class="text-xl font-bold text-slate-900 md:text-2xl">Laba Bersih Otomatis</h3>
                        <p class="mt-4 text-base leading-8 text-slate-600">Sistem otomatis memotong modal admin
                            bank dan mencatat keuntungan bersih tanpa kalkulator.</p>
                    </div>
                </div>

                <!-- Box Kecil 3 -->
                <div
                    class="flex flex-col justify-between rounded-[2rem] border border-slate-200/80 bg-white p-8 shadow-soft transition hover:-translate-y-1 hover:shadow-[0_28px_70px_-35px_rgba(15,23,42,0.3)]">
                    <div>
                        <div
                            class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-6 font-bold text-2xl">
                            📊</div>
                        <h3 class="text-xl font-bold text-slate-900 md:text-2xl">Mutasi & Saldo Bank</h3>
                        <p class="mt-4 text-base leading-8 text-slate-600">Pantau kecocokan saldo awal dan akhir
                            hari di setiap rekening bank dengan laporan akurat.</p>
                    </div>
                </div>

                <!-- Box Besar 4 (Span 2) -->
                <div
                    class="flex flex-col justify-between rounded-[2rem] border border-slate-800 bg-gradient-to-br from-slate-900 to-blue-950 p-8 text-white shadow-[0_28px_70px_-35px_rgba(15,23,42,0.55)] md:col-span-2 md:p-10">
                    <div>
                        <span
                            class="rounded-xl border border-blue-800 bg-blue-900/50 px-3 py-1.5 text-[11px] font-bold text-blue-300 md:text-xs">Cloud
                            Infrastructure</span>
                        <h3 class="mt-5 text-2xl font-bold leading-tight md:text-3xl">Akses Dari Mana Saja, Tanpa
                            Instalasi Rumit</h3>
                        <p class="mt-4 max-w-lg text-base leading-8 text-slate-300">Berjalan sepenuhnya di
                            browser cloud. Aman dari risiko kehilangan data meskipun perangkat (HP/Komputer) Anda rusak
                            atau hilang.</p>
                    </div>
                    <div
                        class="mt-8 flex flex-wrap items-center gap-6 border-t border-slate-700/50 pt-6 text-sm font-medium text-slate-300">
                        <span class="flex items-center gap-2">🛡️ Enkripsi Keamanan</span>
                        <span class="flex items-center gap-2">☁️ Auto Backup Harian</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- TESTIMONI ("Apa Kata Mereka") -->
    <section id="testimoni" class="border-y border-slate-200/60 bg-white py-24 md:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-16 text-center md:mb-20">
                <span
                    class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600">Testimoni</span>
                <h2 class="mt-6 text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl lg:text-5xl">
                    Apa Kata Mereka?
                </h2>
                <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-slate-600 md:text-lg">
                    Ribuan pemilik agen dan bisnis ritel digital telah beralih ke Omzetly untuk menyederhanakan
                    pembukuan mereka.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:gap-8">
                <!-- Kartu Testimoni 1 -->
                <div class="rounded-[2rem] border border-slate-200/80 bg-slate-50/70 p-8 shadow-soft">
                    <div class="flex text-amber-400 mb-5 text-lg gap-0.5">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-slate-700 text-base leading-relaxed mb-8 italic">
                        "Dulu tiap malam pusing cocokin mutasi rekening dan uang laci fisik. Sejak pakai Omzetly, laba
                        bersih harian langsung kelihatan real-time tanpa pusing. Sangat membantu!"
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700 text-lg">
                            B</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm md:text-base">Budi Santoso</div>
                            <div class="text-[11px] md:text-xs font-medium text-slate-500">Owner, BRILink Mandiri Jaya
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Testimoni 2 -->
                <div class="rounded-[2rem] border border-slate-200/80 bg-slate-50/70 p-8 shadow-soft">
                    <div class="flex text-amber-400 mb-5 text-lg gap-0.5">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-slate-700 text-base leading-relaxed mb-8 italic">
                        "Saya punya 3 cabang, sebelum pakai ini kontrolnya susah minta ampun karena pakai Excel
                        beda-beda. Sekarang tinggal pantau dari HP sambil ngopi. Terbaik."
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center font-bold text-emerald-700 text-lg">
                            R</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm md:text-base">Rina Kartika</div>
                            <div class="text-[11px] md:text-xs font-medium text-slate-500">Owner, Agen Rina Cell & Pay
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kartu Testimoni 3 -->
                <div class="rounded-[2rem] border border-slate-200/80 bg-slate-50/70 p-8 shadow-soft">
                    <div class="flex text-amber-400 mb-5 text-lg gap-0.5">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="text-slate-700 text-base leading-relaxed mb-8 italic">
                        "Tampilan sistemnya gampang banget dimengerti. Karyawan baru saya ajari 10 menit langsung bisa
                        melayani pelanggan dan mencatat transaksi tanpa error."
                    </p>
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center font-bold text-purple-700 text-lg">
                            A</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm md:text-base">Agus Purnomo</div>
                            <div class="text-[11px] md:text-xs font-medium text-slate-500">Operator Cabang Utama</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SOLUSI -->
    <section id="tentang" class="bg-slate-50/60 py-24 md:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                <div>
                    <span
                        class="rounded-full border border-blue-200 bg-white px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600 shadow-soft">Transformasi
                        Digital</span>
                    <h2 class="mt-6 text-3xl font-extrabold tracking-tight leading-tight text-slate-900 md:text-4xl">
                        Waktunya Beralih Dari Buku Tulis Ke Sistem Cloud.
                    </h2>
                    <p class="mt-5 text-base leading-8 text-slate-600 md:text-lg">
                        Banyak owner agen rugi waktu karena harus mencocokkan catatan kertas operator di malam hari.
                        Dengan Omzetly, seluruh mutasi terekam detik itu juga secara transparan.
                    </p>
                    <div class="mt-8 space-y-5">
                        <div class="flex items-start gap-4">
                            <span
                                class="w-6 h-6 shrink-0 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold mt-1">✓</span>
                            <span class="font-semibold text-slate-800 text-base">Mencegah selisih uang kas dan salah
                                catat angka oleh operator.</span>
                        </div>
                        <div class="flex items-start gap-4">
                            <span
                                class="w-6 h-6 shrink-0 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold mt-1">✓</span>
                            <span class="font-semibold text-slate-800 text-base">Laporan bulanan rapi, otomatis
                                kalkulasi, dan siap diunduh (PDF/Excel).</span>
                        </div>
                    </div>
                </div>
                <div
                    class="relative rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-[0_28px_70px_-35px_rgba(15,23,42,0.28)] md:p-8">
                    <div
                        class="pointer-events-none absolute inset-0 rounded-[2rem] bg-gradient-to-tr from-blue-50 to-transparent opacity-50">
                    </div>
                    <div class="relative z-10 mb-5 border-b border-slate-100 pb-3 text-sm font-bold text-slate-900">
                        Aktivitas Sistem
                        Real-Time</div>
                    <div class="relative z-10 space-y-4">
                        <div
                            class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 text-xs shadow-soft md:text-sm">
                            <span class="font-bold text-slate-700">Cabang Utama — Transfer BRI</span>
                            <span class="text-emerald-600 font-extrabold">+Rp 2.500.000</span>
                        </div>
                        <div
                            class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 text-xs shadow-soft md:text-sm">
                            <span class="font-bold text-slate-700">Cabang II — Tarik Tunai Mandiri</span>
                            <span class="text-blue-600 font-extrabold">+Rp 1.000.000</span>
                        </div>
                        <div
                            class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50 p-4 text-xs shadow-soft md:text-sm">
                            <span class="font-bold text-slate-700">Cabang Utama — Setor Tunai BCA</span>
                            <span class="text-emerald-600 font-extrabold">+Rp 5.000.000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PRICING -->
    <section id="harga" class="border-t border-slate-200/60 bg-white py-24 md:py-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto mb-16 max-w-2xl text-center md:mb-20">
                <span
                    class="rounded-full border border-blue-200 bg-blue-50 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600">Paket
                    Langganan</span>
                <h2 class="mt-6 text-3xl font-extrabold tracking-tight text-slate-900 md:text-4xl lg:text-5xl">
                    Investasi Transparan
                </h2>
                <p class="mt-5 text-base leading-8 text-slate-600 md:text-lg">Pilih kapasitas yang sesuai dengan
                    skala bisnis agen Anda hari ini tanpa biaya tersembunyi.</p>
            </div>

            <div class="mx-auto flex max-w-5xl flex-col items-stretch justify-center gap-8 md:flex-row">
                @forelse($plans as $plan)
                    @if ($plan->harga == $plans->max('harga') && $plan->harga > 0)
                        <!-- Paket Populer -->
                        <div
                            class="relative flex w-full flex-col rounded-[2rem] bg-slate-900 p-8 text-white shadow-[0_32px_90px_-35px_rgba(15,23,42,0.7)] md:w-1/2 md:-translate-y-4 md:p-10">
                            <span
                                class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-primary px-5 py-2 text-[11px] font-bold uppercase tracking-[0.18em] shadow-soft">Paling
                                Diminati</span>
                            <div class="text-xl font-bold text-blue-400 mt-2">{{ $plan->nama_paket }}</div>
                            <div class="mt-4 flex items-baseline gap-1.5">
                                <span class="text-4xl md:text-5xl font-extrabold tracking-tight">Rp
                                    {{ number_format($plan->harga, 0, ',', '.') }}</span>
                                <span class="text-sm font-medium text-slate-400">/ bln</span>
                            </div>
                            <p class="mt-4 text-base leading-8 text-slate-300">
                                {{ $plan->deskripsi ?? 'Untuk agen dengan kebutuhan sistem multi-cabang lengkap.' }}
                            </p>
                            <ul class="mt-8 flex-1 space-y-4 text-base text-slate-200">
                                @if (is_array($plan->fitur))
                                    @foreach ($plan->fitur as $fitur)
                                        <li class="flex items-start gap-3">
                                            <span class="text-blue-400 mt-0.5 font-bold">✓</span> {{ $fitur }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                            <a href="{{ route('agen.register') }}"
                                class="mt-10 block w-full rounded-2xl bg-blue-600 py-4 text-center text-base font-bold text-white shadow-[0_24px_60px_-24px_rgba(59,130,246,0.7)] transition hover:-translate-y-0.5 hover:bg-blue-500">
                                Pilih Paket Ini
                            </a>
                        </div>
                    @else
                        <!-- Paket Standar / Gratis -->
                        <div
                            class="flex w-full flex-col rounded-[2rem] border border-slate-200/80 bg-white p-8 shadow-[0_28px_70px_-35px_rgba(15,23,42,0.22)] transition duration-300 hover:-translate-y-1 hover:border-slate-300 md:w-1/2 md:p-10">
                            <div class="text-xl font-bold text-slate-900">{{ $plan->nama_paket }}</div>
                            <div class="mt-4 flex items-baseline gap-1.5">
                                <span class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900">
                                    {{ $plan->harga == 0 ? 'Gratis' : 'Rp ' . number_format($plan->harga, 0, ',', '.') }}
                                </span>
                                @if ($plan->harga > 0)
                                    <span class="text-sm font-medium text-slate-500">/ bln</span>
                                @endif
                            </div>
                            <p class="mt-4 text-base leading-8 text-slate-600">
                                {{ $plan->deskripsi ?? 'Cocok untuk mulai mengelola operasional dasar.' }}
                            </p>
                            <ul class="mt-8 flex-1 space-y-4 text-base font-medium text-slate-700">
                                @if (is_array($plan->fitur))
                                    @foreach ($plan->fitur as $fitur)
                                        <li class="flex items-start gap-3">
                                            <span class="text-blue-600 mt-0.5 font-bold">✓</span> {{ $fitur }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                            <a href="{{ route('agen.register') }}"
                                class="mt-10 block w-full rounded-2xl bg-slate-900 py-4 text-center text-base font-bold text-white shadow-soft transition hover:-translate-y-0.5 hover:bg-slate-800">
                                Mulai Sekarang
                            </a>
                        </div>
                    @endif
                @empty
                    <p class="text-slate-500 text-center py-10 w-full text-lg">Belum ada paket tersedia.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="border-t border-slate-200/60 bg-slate-50/60 py-24 md:py-32">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center md:mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Pertanyaan Umum</h2>
            </div>
            <div class="space-y-4 md:space-y-6">
                <details
                    class="group rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-soft transition-colors open:border-blue-300 md:p-8"
                    open>
                    <summary
                        class="flex cursor-pointer items-center justify-between font-bold text-slate-800 text-base md:text-lg">
                        Apakah data transaksi aman dari operator lain?
                        <span
                            class="text-blue-600 font-mono group-open:rotate-45 transition-transform text-xl ml-4">+</span>
                    </summary>
                    <p class="mt-4 text-base text-slate-600 leading-relaxed">Ya, arsitektur multi-tenancy kami menjamin
                        data antar akun dan cabang terisolasi secara ketat dan terenkripsi. Hak akses operator bisa Anda
                        batasi sesuai kebutuhan.</p>
                </details>
                <details
                    class="group rounded-[2rem] border border-slate-200/80 bg-white p-6 shadow-soft transition-colors open:border-blue-300 md:p-8">
                    <summary
                        class="flex cursor-pointer items-center justify-between font-bold text-slate-800 text-base md:text-lg">
                        Bisakah diakses menggunakan handphone?
                        <span
                            class="text-blue-600 font-mono group-open:rotate-45 transition-transform text-xl ml-4">+</span>
                    </summary>
                    <p class="mt-4 text-base text-slate-600 leading-relaxed">Tentu. Omzetly responsif penuh dan sangat
                        nyaman diakses lewat
                        smartphone (HP) maupun komputer tablet milik operator.</p>
                </details>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-slate-200 bg-slate-950 py-16 text-white md:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div
                class="grid grid-cols-1 gap-12 rounded-[2rem] border border-white/10 bg-white/5 p-8 pb-10 md:grid-cols-12 lg:gap-16 lg:p-10">

                <!-- Sisi Kiri: Brand & Live Status Badge -->
                <div class="md:col-span-5 lg:col-span-6 flex flex-col justify-between">
                    <div class="space-y-6">
                        <div class="flex items-center gap-2.5">
                            <div
                                class="h-10 w-10 md:h-12 md:w-12 rounded-xl overflow-hidden shadow-md bg-white flex items-center justify-center">
                                <img src="{{ asset('assets/images/logo/omzetly.png') }}" alt="Omzetly"
                                    class="h-full w-full object-contain">
                            </div>
                            <span class="font-extrabold text-white text-xl md:text-2xl tracking-tight">Omzetly<span
                                    class="text-blue-500">.id</span></span>
                        </div>
                        <p class="max-w-sm text-sm leading-7 text-slate-400 md:text-base">
                            Platform cloud mutakhir untuk pencatatan transaksi, manajemen kas multi-cabang, dan
                            kalkulasi laba otomatis bagi agen digital.
                        </p>
                    </div>

                    <!-- Indikator Server Live -->
                    <div
                        class="mt-8 inline-flex w-fit items-center gap-3 rounded-full border border-slate-700/60 bg-slate-800/80 px-5 py-2.5 text-xs font-medium text-slate-300 backdrop-blur-sm md:mt-10 md:text-sm">
                        <span class="relative flex h-3 w-3">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        Semua Sistem Beroperasi Normal
                    </div>
                </div>

                <!-- Sisi Kanan: Menu Kolom -->
                <div class="md:col-span-7 lg:col-span-6 grid grid-cols-2 sm:grid-cols-3 gap-8">
                    <div>
                        <h4 class="text-[11px] md:text-xs font-bold uppercase tracking-widest text-slate-500 mb-5">
                            Navigasi</h4>
                        <ul class="space-y-3.5 text-sm md:text-base font-medium text-slate-300">
                            <li><a href="#beranda" class="hover:text-blue-400 transition">Beranda</a></li>
                            <li><a href="#fitur" class="hover:text-blue-400 transition">Fitur Utama</a></li>
                            <li><a href="#harga" class="hover:text-blue-400 transition">Daftar Harga</a></li>
                            <li><a href="#faq" class="hover:text-blue-400 transition">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[11px] md:text-xs font-bold uppercase tracking-widest text-slate-500 mb-5">
                            Akses Cepat</h4>
                        <ul class="space-y-3.5 text-sm md:text-base font-medium text-slate-300">
                            <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Masuk Akun</a>
                            </li>
                            <li><a href="{{ route('agen.register') }}" class="hover:text-blue-400 transition">Daftar
                                    Agen</a></li>
                            <li><a href="#tentang" class="hover:text-blue-400 transition">Solusi Cabang</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-[11px] md:text-xs font-bold uppercase tracking-widest text-slate-500 mb-5">
                            Legal</h4>
                        <ul class="space-y-3.5 text-sm md:text-base font-medium text-slate-300">
                            <li><a href="#" class="hover:text-blue-400 transition">Kebijakan Privasi</a></li>
                            <li><a href="#" class="hover:text-blue-400 transition">Syarat Ketentuan</a></li>
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Bagian Bawah: Copyright -->
            <div
                class="mt-8 flex flex-col items-center justify-between gap-4 text-xs font-medium text-slate-400 md:text-sm sm:flex-row">
                <p>&copy; 2026 Omzetly.id. Seluruh hak cipta dilindungi undang-undang.</p>
                <p class="flex items-center gap-1.5">
                    Dibuat dengan presisi untuk <span class="text-white font-semibold">Agen Indonesia</span> 🇮🇩
                </p>
            </div>
        </div>
    </footer>

    <script>
        function switchTab(tabId) {
            // Sembunyikan semua panel gambar
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
                panel.classList.remove('block');
            });

            // Reset semua tombol ke gaya tidak aktif
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-slate-900', 'text-white', 'shadow-md');
                btn.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
            });

            // Tampilkan gambar yang dipilih
            const selectedImg = document.getElementById('img-' + tabId);
            const selectedPanel = selectedImg.closest('.tab-panel');
            selectedPanel.classList.remove('hidden');
            selectedPanel.classList.add('block');

            // Ubah gaya tombol yang aktif
            const selectedBtn = document.getElementById('btn-' + tabId);
            selectedBtn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
            selectedBtn.classList.add('bg-slate-900', 'text-white', 'shadow-md');
        }



        document.addEventListener('DOMContentLoaded', function() {
            // Typing Animation
            const textArray = [
                "Agen Digital Modern",
                "Agen BRILink Cerdas",
                "Keuangan Terkontrol",
                "Bisnis Lebih Terukur"
            ];
            let textIndex = 0;
            let charIndex = 0;
            let isDeleting = false;
            const typeDelay = 100;
            const eraseDelay = 50;
            const newTextDelay = 2000;
            const typedTextSpan = document.getElementById("typed-text");

            function type() {
                const currentText = textArray[textIndex];
                if (isDeleting) {
                    typedTextSpan.textContent = currentText.substring(0, charIndex - 1);
                    charIndex--;
                } else {
                    typedTextSpan.textContent = currentText.substring(0, charIndex + 1);
                    charIndex++;
                }

                let delay = isDeleting ? eraseDelay : typeDelay;

                if (!isDeleting && charIndex === currentText.length) {
                    delay = newTextDelay;
                    isDeleting = true;
                } else if (isDeleting && charIndex === 0) {
                    isDeleting = false;
                    textIndex++;
                    if (textIndex >= textArray.length) {
                        textIndex = 0;
                    }
                    delay = 500;
                }
                setTimeout(type, delay);
            }

            if (typedTextSpan) {
                setTimeout(type, 500);
            }
        });

        // Navbar glass effect
        const nav = document.getElementById('nav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) nav.firstElementChild.classList.add('glass', 'border-slate-200',
                'shadow-soft');
            else nav.firstElementChild.classList.remove('glass', 'border-slate-200', 'shadow-soft');
        });

        // Mobile Menu toggle
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const iconMenu = document.getElementById('icon-menu');
        const iconClose = document.getElementById('icon-close');

        // Agar ketika link mobile ditekan, menu langsung tertutup
        const mobileLinks = mobileMenu.querySelectorAll('a');

        function toggleMenu() {
            mobileMenu.classList.toggle('hidden');
            iconMenu.classList.toggle('hidden');
            iconClose.classList.toggle('hidden');
        }

        menuBtn.addEventListener('click', toggleMenu);

        mobileLinks.forEach(link => {
            link.addEventListener('click', toggleMenu);
        });
    </script>
</body>

</html>
