<nav class="bg-[#1d62fb] border-b border-blue-700 fixed w-full z-20 top-0 start-0">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-14 items-center justify-between">
            <div class="flex items-center gap-4">
                <span
                    class="text-white font-extrabold select-none cursor-default text-xl sm:text-2xl transition-all truncate max-w-[160px] sm:max-w-none">
                    @auth
                        {{ Auth::user()->tenant->nama_toko ?? 'Tanpa Toko' }}
                    @endauth
                </span>

                <ul class="hidden md:flex items-center gap-1 text-sm">
                    {{-- Home --}}
                    <li>
                        <a href="{{ route('main') }}"
                            class="flex items-center gap-1.5 px-3 py-2 rounded-lg transition-colors
                                {{ request()->routeIs('main') ? 'text-white font-semibold bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path
                                    d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                            </svg>
                            Home
                        </a>
                    </li>

                    {{-- Transaksi --}}
                    <li class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                        <button type="button" x-on:click="open = !open"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors focus:outline-none
                                {{ request()->routeIs('transaksi-bank') || request()->routeIs('vouchers') ? 'text-white font-semibold bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path d="M21 12H3"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                            </svg>
                            Transaksi
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                viewBox="0 0 24 24">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                            class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-30">
                            {{-- <a href="{{ route('vouchers') }}"
                                class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors
                                    {{ request()->routeIs('vouchers') ? 'text-blue-600 font-semibold' : '' }}">
                                Transaksi Konter
                                <span class="text-xs text-gray-400 bg-gray-100 rounded px-1.5 py-0.5">Beta</span>
                            </a> --}}
                            <a href="{{ route('transaksi-bank') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors
                                    {{ request()->routeIs('transaksi-bank') ? 'text-blue-600 font-semibold' : '' }}">
                                Transaksi Bank
                            </a>
                        </div>
                    </li>

                    {{-- Laporan --}}
                    <li class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                        <button type="button" x-on:click="open = !open"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors focus:outline-none
                                {{ request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap') || request()->routeIs('laporan_konter') ? 'text-white font-semibold bg-white/10' : 'text-blue-200 hover:text-white hover:bg-white/10' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <path
                                    d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2" />
                            </svg>
                            Laporan
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                viewBox="0 0 24 24">
                                <path d="M6 9l6 6 6-6"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                            class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-30">
                            {{-- <a href="{{ route('laporan_konter') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors
                                    {{ request()->routeIs('laporan_konter') ? 'text-blue-600 font-semibold' : '' }}">
                                Laporan Konter
                            </a> --}}
                            <a href="{{ route('laporan-bank') }}"
                                class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors
                                    {{ request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap') ? 'text-blue-600 font-semibold' : '' }}">
                                Laporan Bank
                            </a>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="relative" x-data="{ open: false }" x-on:click.outside="open = false">
                <button type="button" x-on:click="open = !open"
                    class="flex items-center gap-2 text-white text-sm font-medium hover:bg-white/10 rounded-lg px-2 py-1.5 transition-colors focus:outline-none">
                    <span
                        class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-xs font-semibold shrink-0">
                        @auth
                            {{ strtoupper(substr(Auth::user()->name ?? '?', 0, 1)) }}
                        @endauth
                    </span>
                <span class="hidden sm:inline max-w-[100px] truncate">@auth{{ Auth::user()->name }}</span> @endauth
                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    viewBox="0 0 24 24">
                    <path d="M6 9l6 6 6-6"></path>
                </svg>
            </button>

            <div x-show="open" x-cloak
                class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 z-30">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</nav>

{{-- Mobile Nav Bottom (Clean Light Dock - Menyatu dengan Tema Web) --}}
<div class="fixed md:hidden bottom-0 inset-x-0 z-50 pointer-events-none" x-data="{ transaksiOpen: false, laporanOpen: false }">

<div
    class="bg-white/95 backdrop-blur-xl border-t border-slate-200/80 shadow-[0_-10px_30px_rgba(0,0,0,0.06)] flex items-center justify-around h-16 w-full px-4 relative pointer-events-auto pb-safe">

    {{-- 1. Menu Home --}}
    @php $isHome = request()->routeIs('main'); @endphp
    <a href="{{ route('main') }}"
        class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-300 {{ $isHome ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600' }}">

        <div
            class="flex flex-col items-center gap-1 transition-transform duration-300 {{ $isHome ? '-translate-y-1' : '' }}">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" viewBox="0 0 24 24">
                <path
                    d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
            </svg>
            <span class="text-[10px] {{ $isHome ? 'font-bold' : 'font-medium' }}">Beranda</span>
        </div>

        @if ($isHome)
            <span
                class="absolute bottom-1.5 w-1.5 h-1.5 bg-blue-600 rounded-full shadow-[0_0_6px_rgba(37,99,235,0.6)]"></span>
        @endif
    </a>

    {{-- 2. Menu Transaksi --}}
    @php $isTransaksi = request()->routeIs('transaksi-bank') || request()->routeIs('vouchers'); @endphp
    <div class="relative flex items-center justify-center w-full h-full">
        <button type="button" x-on:click="transaksiOpen = !transaksiOpen; laporanOpen = false"
            class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-300 outline-none {{ $isTransaksi ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600' }}">

            <div
                class="flex flex-col items-center gap-1 transition-transform duration-300 {{ $isTransaksi ? '-translate-y-1' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path
                        d="M17 8H5m12 0a1 1 0 0 1 1 1v2.6M17 8l-4-4M5 8a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.6M5 8l4-4 4 4m6 4h-4a2 2 0 1 0 0 4h4a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1Z" />
                </svg>
                <span class="text-[10px] {{ $isTransaksi ? 'font-bold' : 'font-medium' }}">Transaksi</span>
            </div>

            @if ($isTransaksi)
                <span
                    class="absolute bottom-1.5 w-1.5 h-1.5 bg-blue-600 rounded-full shadow-[0_0_6px_rgba(37,99,235,0.6)]"></span>
            @endif
        </button>

        <!-- Dropup Transaksi (Light Theme) -->
        <div x-show="transaksiOpen" x-cloak x-on:click.outside="transaksiOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 scale-95"
            class="absolute bottom-[calc(100%+12px)] left-1/2 -translate-x-1/2 w-52 bg-white backdrop-blur-xl border border-slate-200 rounded-[1.5rem] shadow-2xl p-2 overflow-hidden">

            @php $isTxBank = request()->routeIs('transaksi-bank'); @endphp
            <a href="{{ route('transaksi-bank') }}"
                class="flex items-center gap-3 px-4 py-3.5 text-sm rounded-xl transition-colors duration-200 {{ $isTxBank ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="p-1.5 rounded-lg {{ $isTxBank ? 'bg-blue-200/50' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                Transaksi Bank
            </a>

            {{-- @php $isTxKonter = request()->routeIs('vouchers'); @endphp
            <a href="{{ route('vouchers') }}"
                class="flex items-center gap-3 px-4 py-3.5 text-sm rounded-xl transition-colors duration-200 mt-1 {{ $isTxKonter ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <div
                    class="p-1.5 rounded-lg {{ $isTxKonter ? 'bg-blue-200/50' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                Transaksi Konter
            </a> --}}
        </div>
    </div>

    {{-- 3. Menu Laporan --}}
    @php $isLaporan = request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap') || request()->routeIs('laporan_konter'); @endphp
    <div class="relative flex items-center justify-center w-full h-full">
        <button type="button" x-on:click="laporanOpen = !laporanOpen; transaksiOpen = false"
            class="relative flex flex-col items-center justify-center w-full h-full transition-all duration-300 outline-none {{ $isLaporan ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600' }}">

            <div
                class="flex flex-col items-center gap-1 transition-transform duration-300 {{ $isLaporan ? '-translate-y-1' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path
                        d="M6 4v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2m6-16v2m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v10m6-16v10m0 0a2 2 0 1 0 0 4m0-4a2 2 0 1 1 0 4m0 0v2" />
                </svg>
                <span class="text-[10px] {{ $isLaporan ? 'font-bold' : 'font-medium' }}">Laporan</span>
            </div>

            @if ($isLaporan)
                <span
                    class="absolute bottom-1.5 w-1.5 h-1.5 bg-blue-600 rounded-full shadow-[0_0_6px_rgba(37,99,235,0.6)]"></span>
            @endif
        </button>

        <!-- Dropup Laporan (Light Theme) -->
        <div x-show="laporanOpen" x-cloak x-on:click.outside="laporanOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 scale-95"
            class="absolute bottom-[calc(100%+12px)] left-1/2 -translate-x-1/2 w-52 bg-white backdrop-blur-xl border border-slate-200 rounded-[1.5rem] shadow-2xl p-2 overflow-hidden">

            @php $isLapBank = request()->routeIs('laporan-bank') || request()->routeIs('laporan-bank.rekap'); @endphp
            <a href="{{ route('laporan-bank') }}"
                class="flex items-center gap-3 px-4 py-3.5 text-sm rounded-xl transition-colors duration-200 {{ $isLapBank ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <div class="p-1.5 rounded-lg {{ $isLapBank ? 'bg-blue-200/50' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                Laporan Bank
            </a>

            {{-- @php $isLapKonter = request()->routeIs('laporan_konter'); @endphp
            <a href="{{ route('laporan_konter') }}"
                class="flex items-center gap-3 px-4 py-3.5 text-sm rounded-xl transition-colors duration-200 mt-1 {{ $isLapKonter ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-700 hover:bg-slate-50' }}">
                <div
                    class="p-1.5 rounded-lg {{ $isLapKonter ? 'bg-blue-200/50' : 'bg-slate-100 text-slate-500' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                Laporan Konter
            </a> --}}
        </div>
    </div>

</div>
</div>
