@extends('layouts.app')

@section('title', 'Rekap')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-4 sm:space-y-6 pb-12 mt-3 sm:mt-5">
        {{-- Header & Tombol Export --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 w-full flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                        <span class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        Rekapitulasi Bisnis
                    </h1>
                    <p class="text-sm text-slate-500 mt-2 font-medium hidden sm:block">Laporan tanggal: <span
                            class="text-slate-800 font-bold">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</span>
                    </p>
                    <p
                        class="text-xs text-slate-500 mt-2 font-medium sm:hidden flex items-center gap-1.5 bg-slate-50 w-fit px-2.5 py-1 rounded-md border border-slate-100">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                    </p>
                </div>

                {{-- Ubah menjadi grid-cols-2 di mobile agar tombol sejajar rapi, tidak bertumpuk kaku --}}
                <div class="grid grid-cols-2 sm:flex sm:flex-nowrap items-center gap-2 sm:gap-2.5 w-full md:w-auto">
                    <a href="{{ route('rekap.pdf', request()->only(['tanggal', 'cabang_id'])) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('rekap.excel', request()->only(['tanggal', 'cabang_id'])) }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Export Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-2">
            <button type="button" onclick="toggleFilter()"
                class="w-full flex md:hidden items-center justify-between p-4 bg-slate-50 text-slate-700 hover:bg-slate-100 transition-colors active:bg-slate-200">
                <span class="font-bold text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>Pencarian & Filter Data
                </span>
                <svg id="filterIcon" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div id="filterBody" class="hidden md:block p-4 sm:p-5 border-t border-slate-200 md:border-t-0">
                <form method="GET" class="flex flex-col md:flex-row flex-wrap gap-3 sm:gap-4 items-end">
                    <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                        <label for="tanggal" class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Tanggal
                            Laporan</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal', $tanggal) }}"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-colors outline-none appearance-none">
                    </div>
                    <div class="w-full sm:w-auto flex-1 min-w-[200px]">
                        <label for="cabang_id" class="block text-xs sm:text-sm font-medium text-slate-700 mb-1.5">Pilih
                            Cabang</label>
                        <select name="cabang_id"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-colors outline-none">
                            <option value="semua"
                                {{ !request('cabang_id') || request('cabang_id') == 'semua' ? 'selected' : '' }}>Semua
                                Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}"
                                    {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full md:w-auto mt-1 md:mt-0">
                        <button type="submit"
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all outline-none active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @php
            $profit = ($totalOmzet ?? 0) - ($totalPengeluaran ?? 0);
            $totalNonKas = ($totalTransfer ?? 0) + ($totalTarikTunai ?? 0) + ($totalNumpang ?? 0);
            $rataOmzet = $totalNonKas > 0 ? ($totalOmzet ?? 0) / $totalNonKas : 0;
        @endphp

        {{-- ✅ Card Utama --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">

            {{-- Laba Kotor --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-blue-50 rounded-full blur-xl transition-all group-hover:bg-blue-100 group-hover:scale-150">
                </div>
                <div class="relative z-10 flex items-center gap-3 mb-2 sm:mb-3">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-50 rounded-lg flex items-center justify-center border border-blue-100 text-blue-600">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Laba Kotor</p>
                </div>
                <p class="relative z-10 text-lg sm:text-2xl font-extrabold text-blue-600 truncate">Rp
                    {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</p>
                <p class="relative z-10 text-[10px] sm:text-xs text-slate-400 mt-1 font-medium">Omzet transaksi operasional
                </p>
            </div>

            {{-- Biaya Operasional --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-rose-50 rounded-full blur-xl transition-all group-hover:bg-rose-100 group-hover:scale-150">
                </div>
                <div class="relative z-10 flex items-center gap-3 mb-2 sm:mb-3">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-rose-50 rounded-lg flex items-center justify-center border border-rose-100 text-rose-600">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
                        </svg>
                    </div>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Biaya Ops</p>
                </div>
                <p class="relative z-10 text-lg sm:text-2xl font-extrabold text-rose-600 truncate">Rp
                    {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                <p class="relative z-10 text-[10px] sm:text-xs text-slate-400 mt-1 font-medium">Listrik, PDAM, Internet</p>
            </div>

            {{-- Profit --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-emerald-50 rounded-full blur-xl transition-all group-hover:bg-emerald-100 group-hover:scale-150">
                </div>
                <div class="relative z-10 flex items-center gap-3 mb-2 sm:mb-3">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-emerald-50 rounded-lg flex items-center justify-center border border-emerald-100 text-emerald-600">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Profit</p>
                </div>
                <p
                    class="relative z-10 text-lg sm:text-2xl font-extrabold {{ $profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} truncate">
                    {{ $profit >= 0 ? '' : '-' }}Rp {{ number_format(abs($profit), 0, ',', '.') }}
                </p>
                <p class="relative z-10 text-[10px] sm:text-xs text-slate-400 mt-1 font-medium">Laba Kotor - Biaya</p>
            </div>

            {{-- Rata Omzet/Trx --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-indigo-50 rounded-full blur-xl transition-all group-hover:bg-indigo-100 group-hover:scale-150">
                </div>
                <div class="relative z-10 flex items-center gap-3 mb-2 sm:mb-3">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-indigo-50 rounded-lg flex items-center justify-center border border-indigo-100 text-indigo-600">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Rata/Trx</p>
                </div>
                <p class="relative z-10 text-lg sm:text-2xl font-extrabold text-indigo-600 truncate">Rp
                    {{ number_format($rataOmzet ?? 0, 0, ',', '.') }}</p>
                <p class="relative z-10 text-[10px] sm:text-xs text-slate-400 mt-1 font-medium">Omzet / Total Transaksi</p>
            </div>

            {{-- Saldo Kas --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-purple-50 rounded-full blur-xl transition-all group-hover:bg-purple-100 group-hover:scale-150">
                </div>
                <div class="relative z-10 flex items-center gap-3 mb-2 sm:mb-3">
                    <div
                        class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-50 rounded-lg flex items-center justify-center border border-purple-100 text-purple-600">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                    <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Saldo Kas</p>
                </div>
                <p class="relative z-10 text-lg sm:text-2xl font-extrabold text-slate-800 truncate">Rp
                    {{ number_format($totalSaldoKas ?? 0, 0, ',', '.') }}</p>
                <p class="relative z-10 text-[10px] sm:text-xs text-slate-400 mt-1 font-medium">
                    <span class="text-emerald-500 font-bold">+{{ $totalPenambahanKas ?? 0 }}</span> in,
                    <span class="text-rose-500 font-bold">-{{ $totalPenguranganKas ?? 0 }}</span> out
                </p>
            </div>
        </div>

        {{-- Card Sekunder --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3">
            <div class="bg-white rounded-2xl border border-slate-200 p-3 sm:p-4 text-center shadow-sm">
                <p class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider">Total Trx</p>
                <p class="text-lg sm:text-xl font-extrabold text-slate-700 mt-1">{{ number_format($totalTransaksi ?? 0) }}
                </p>
            </div>
            <div class="bg-blue-50/50 rounded-2xl border border-blue-100 p-3 sm:p-4 text-center shadow-sm">
                <p class="text-[10px] sm:text-xs font-bold text-blue-500 uppercase tracking-wider">Transfer</p>
                <p class="text-lg sm:text-xl font-extrabold text-blue-700 mt-1">{{ number_format($totalTransfer ?? 0) }}
                </p>
            </div>
            <div class="bg-amber-50/50 rounded-2xl border border-amber-100 p-3 sm:p-4 text-center shadow-sm">
                <p class="text-[10px] sm:text-xs font-bold text-amber-500 uppercase tracking-wider">Tarik Tunai</p>
                <p class="text-lg sm:text-xl font-extrabold text-amber-700 mt-1">
                    {{ number_format($totalTarikTunai ?? 0) }}</p>
            </div>
            <div class="bg-indigo-50/50 rounded-2xl border border-indigo-100 p-3 sm:p-4 text-center shadow-sm">
                <p class="text-[10px] sm:text-xs font-bold text-indigo-500 uppercase tracking-wider">Numpang</p>
                <p class="text-lg sm:text-xl font-extrabold text-indigo-700 mt-1">{{ number_format($totalNumpang ?? 0) }}
                </p>
            </div>
            <div class="bg-emerald-50/50 rounded-2xl border border-emerald-100 p-3 sm:p-4 text-center shadow-sm">
                <p class="text-[10px] sm:text-xs font-bold text-emerald-500 uppercase tracking-wider">Tambah Kas</p>
                <p class="text-lg sm:text-xl font-extrabold text-emerald-700 mt-1">
                    {{ number_format($totalPenambahanKas ?? 0) }}</p>
            </div>
            <div class="bg-rose-50/50 rounded-2xl border border-rose-100 p-3 sm:p-4 text-center shadow-sm">
                <p class="text-[10px] sm:text-xs font-bold text-rose-500 uppercase tracking-wider">Kurang Kas</p>
                <p class="text-lg sm:text-xl font-extrabold text-rose-700 mt-1">
                    {{ number_format($totalPenguranganKas ?? 0) }}</p>
            </div>
        </div>

        {{-- Grid Rekap per Bank & Cabang --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

            {{-- Rekap per Bank --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>Rekap per Bank
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 sm:px-5 py-2 sm:py-3">Bank</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-center">Trx</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-right">Debit</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-right">Kredit</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-right">Saldo</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                            @forelse ($rekapBank as $bank)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 font-bold text-slate-700">{{ $bank['nama'] }}
                                    </td>
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 text-center">{{ $bank['total_trx'] }}</td>
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 text-right text-emerald-600">Rp
                                        {{ number_format($bank['debit'], 0, ',', '.') }}</td>
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 text-right text-rose-500">Rp
                                        {{ number_format($bank['kredit'], 0, ',', '.') }}</td>
                                    <td
                                        class="px-4 sm:px-5 py-2.5 sm:py-3 text-right font-bold {{ $bank['saldo'] >= 0 ? 'text-slate-800' : 'text-rose-600' }}">
                                        Rp {{ number_format($bank['saldo'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-slate-400 text-xs sm:text-sm">Tidak
                                        ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Rekap per Cabang --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                    <h2 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>Rekap per Cabang
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 sm:px-5 py-2 sm:py-3">Cabang</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-center">Trx</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-right">Omzet</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-right">Biaya</th>
                                <th class="px-4 sm:px-5 py-2 sm:py-3 text-right">Profit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                            @forelse ($rekapCabang as $cabang)
                                <tr class="hover:bg-slate-50/70">
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 font-bold text-slate-700">{{ $cabang['nama'] }}
                                    </td>
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 text-center">{{ $cabang['total_trx'] }}</td>
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 text-right text-blue-600 font-medium">Rp
                                        {{ number_format($cabang['omzet'], 0, ',', '.') }}</td>
                                    <td class="px-4 sm:px-5 py-2.5 sm:py-3 text-right text-rose-500 font-medium">Rp
                                        {{ number_format($cabang['pengeluaran'], 0, ',', '.') }}</td>
                                    <td
                                        class="px-4 sm:px-5 py-2.5 sm:py-3 text-right font-bold {{ ($cabang['profit'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ ($cabang['profit'] ?? 0) >= 0 ? '' : '-' }}Rp
                                        {{ number_format(abs($cabang['profit'] ?? 0), 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-6 text-slate-400 text-xs sm:text-sm">Tidak
                                        ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ✅ Rekap per User --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm sm:text-base font-bold text-slate-800 flex items-center gap-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>Rekap Kinerja User
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 sm:px-6 py-2 sm:py-3">User</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3">Cabang</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 text-center">Trx</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 text-right">Laba Kotor</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 text-right">Biaya</th>
                            <th class="px-4 sm:px-6 py-2 sm:py-3 text-right">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse ($rekapUser as $user)
                            @php
                                $userProfit = ($user['omzet'] ?? 0) - ($user['pengeluaran'] ?? 0);
                            @endphp
                            <tr class="hover:bg-slate-50/70">
                                <td class="px-4 sm:px-6 py-2.5 sm:py-4 font-bold text-slate-700">{{ $user['nama'] }}</td>
                                <td class="px-4 sm:px-6 py-2.5 sm:py-4">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] sm:text-xs font-bold bg-slate-100 text-slate-600">{{ $user['cabang'] }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-2.5 sm:py-4 text-center font-bold">
                                    {{ number_format($user['total_trx']) }}</td>
                                <td class="px-4 sm:px-6 py-2.5 sm:py-4 text-right font-medium text-blue-600">Rp
                                    {{ number_format($user['omzet'] ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 sm:px-6 py-2.5 sm:py-4 text-right font-medium text-rose-500">Rp
                                    {{ number_format($user['pengeluaran'] ?? 0, 0, ',', '.') }}</td>
                                <td
                                    class="px-4 sm:px-6 py-2.5 sm:py-4 text-right font-extrabold {{ $userProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $userProfit >= 0 ? '' : '-' }}Rp {{ number_format(abs($userProfit), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-400 text-xs sm:text-sm">Tidak ada
                                    data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-sm sm:text-base font-bold text-slate-800 mb-3 sm:mb-4">Tren Omzet 7 Hari</h2>
                <div class="w-full h-56 sm:h-72"><canvas id="chartOmzet7Hari"></canvas></div>
            </div>
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
                <h2 class="text-sm sm:text-base font-bold text-slate-800 mb-3 sm:mb-4">Transaksi per Jam</h2>
                <div class="w-full h-56 sm:h-72"><canvas id="chartPerJam"></canvas></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleFilter() {
            const fb = document.getElementById('filterBody'),
                fi = document.getElementById('filterIcon');
            if (fb.classList.contains('hidden')) {
                fb.classList.remove('hidden');
                fb.classList.add('block');
                fi.classList.add('rotate-180');
            } else {
                fb.classList.add('hidden');
                fb.classList.remove('block');
                fi.classList.remove('rotate-180');
            }
        }

        new Chart(document.getElementById('chartOmzet7Hari'), {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsOmzet7Hari ?? []) !!},
                datasets: [{
                        label: 'Omzet',
                        data: {!! json_encode($dataOmzet7Hari ?? []) !!},
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37,99,235,0.08)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Pengeluaran',
                        data: {!! json_encode($dataPengeluaran7Hari ?? []) !!},
                        borderColor: '#dc2626',
                        backgroundColor: 'rgba(220,38,38,0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#dc2626',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    },
                    {
                        label: 'Profit',
                        data: {!! json_encode($dataProfit7Hari ?? []) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,0.08)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#059669',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 10
                            },
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx
                                    .parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            callback: function(v) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                                    notation: 'compact'
                                }).format(v);
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartPerJam'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelsPerJam ?? []) !!},
                datasets: [{
                    label: 'Transaksi',
                    data: {!! json_encode($dataPerJam ?? []) !!},
                    backgroundColor: '#4f46e5',
                    hoverBackgroundColor: '#4338ca',
                    borderRadius: 6,
                    barThickness: 'flex',
                    maxBarThickness: 36
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ctx.parsed.y + ' Transaksi';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            },
                            callback: function(v) {
                                return v + ' Trx';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
