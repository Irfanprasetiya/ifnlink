@extends('layouts.app')

@section('title', 'Laporan Laba Rugi')

@section('container')
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Header & Tombol Export --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5 relative">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                    <span class="p-2.5 bg-blue-600 text-white rounded-xl shadow-sm shadow-blue-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 13v-1m4 1v-3m4 3V8M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </span>
                    Laba Rugi Per Cabang
                </h1>
                <p class="text-sm text-slate-500 mt-2 font-medium">
                    Evaluasi performa finansial, profitabilitas, dan pengeluaran setiap cabang.
                </p>
            </div>

            <div class="flex flex-row gap-3">
                {{-- Tombol PDF --}}
                <a href="{{ route('laba_rugi.pdf', request()->query()) }}" title="Unduh PDF"
                    class="inline-flex items-center justify-center gap-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 font-bold px-4 py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline-block">Cetak PDF</span>
                </a>

                {{-- Tombol Excel --}}
                <a href="{{ route('laba_rugi.excel', request()->query()) }}" title="Unduh Excel"
                    class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl text-sm shadow-sm shadow-emerald-600/20 transition-all active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="hidden sm:inline-block">Export Excel</span>
                </a>
            </div>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl text-sm font-medium flex items-center gap-3 shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                {{ session('success') }}
            </div>
        @endif

        {{-- Ringkasan Global (Hero Metric) - High Contrast --}}
        <div
            class="bg-gradient-to-br from-slate-900 via-blue-950 to-blue-900 rounded-2xl shadow-xl overflow-hidden relative border border-blue-500/30">
            <!-- Dekorasi Background Lembut -->
            <div
                class="absolute top-0 right-0 -mt-12 -mr-12 w-56 h-56 bg-blue-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div
                class="relative z-10 p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">

                <!-- Bagian Kiri: Informasi Laba Bersih -->
                <div class="flex items-center gap-4 sm:gap-5">
                    <!-- Kotak Ikon -->
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-600/30 rounded-2xl border border-blue-400/30 flex items-center justify-center shrink-0 shadow-inner">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <!-- Teks -->
                    <div>
                        <h2 class="text-slate-300 font-bold text-xs sm:text-sm uppercase tracking-wider mb-1.5">
                            Total Laba Bersih Seluruh Cabang
                        </h2>
                        <div class="flex items-baseline">
                            <span class="text-xl sm:text-2xl font-bold text-blue-400 mr-1.5">Rp</span>
                            <span
                                class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight drop-shadow-sm">
                                {{ number_format($totalLabaBersih ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Bagian Kanan: Periode Laporan -->
                <div
                    class="flex items-center gap-3 bg-slate-900/80 border border-slate-700/80 rounded-2xl px-4 py-3 shrink-0 w-full sm:w-auto shadow-sm">
                    <div class="p-2 bg-blue-500/20 rounded-xl shrink-0 border border-blue-500/30">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div
                        class="flex flex-row sm:flex-col items-center sm:items-start justify-between w-full sm:w-auto gap-2 sm:gap-0.5">
                        <span class="text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-wide">Periode
                            Laporan</span>
                        <span class="text-sm font-bold text-white">
                            {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M y') }} -
                            {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M y') }}
                        </span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal
                        Awal</label>
                    <div class="relative">
                        <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 p-3 transition-all cursor-pointer">
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal
                        Akhir</label>
                    <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 p-3 transition-all cursor-pointer">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih
                        Cabang</label>
                    <select name="cabang_id"
                        class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm font-semibold rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 p-3 transition-all appearance-none cursor-pointer">
                        <option value="">-- Semua Cabang --</option>
                        @foreach ($allCabangs as $cabang)
                            <option value="{{ $cabang->id }}" {{ $cabang_id == $cabang->id ? 'selected' : '' }}>
                                {{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white font-bold px-6 py-3 rounded-xl text-sm shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabel Data Per Cabang --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-extrabold text-slate-800">Rincian Performa Cabang</h2>
                <span class="bg-slate-100 text-slate-600 text-xs font-bold px-3 py-1 rounded-lg border border-slate-200">
                    {{ count($cabangs) }} Cabang
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80 border-b border-slate-200">
                        <tr class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider text-left">
                            <th class="px-6 py-4 w-12 text-center">No</th>
                            <th class="px-6 py-4">Nama Cabang</th>

                            <th class="px-6 py-4 text-right">Laba Kotor</th>
                            <th class="px-6 py-4 text-right">Pengeluaran</th>

                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-right">Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($cabangs as $index => $cabang)
                            @php
                                $laba = $labaBersih[$cabang->id] ?? 0;
                                $isProfit = $laba >= 0;
                            @endphp
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-slate-50 flex items-center justify-center shrink-0 border border-slate-200 text-slate-500 group-hover:border-blue-300 group-hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <span class="font-extrabold text-slate-800">{{ $cabang->nama_cabang }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right text-slate-600 font-medium">
                                    Rp {{ number_format($labaKotor[$cabang->id] ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-right text-slate-600 font-medium">
                                    Rp {{ number_format($pengeluaran[$cabang->id] ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-[10px] font-extrabold uppercase tracking-wider border shadow-sm {{ $isProfit ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                                        @if ($isProfit)
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                            </svg>
                                            Profit
                                        @else
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                            </svg>
                                            Rugi
                                        @endif
                                    </span>
                                </td>

                                <td
                                    class="px-6 py-4 text-right font-extrabold text-base {{ $isProfit ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $isProfit ? '+' : '-' }} Rp {{ number_format(abs($laba), 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-16 bg-slate-50/50">
                                    <div class="flex flex-col items-center justify-center text-slate-500">
                                        <div
                                            class="w-16 h-16 bg-white rounded-full flex items-center justify-center border border-slate-200 shadow-sm mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                        <p class="text-base font-bold text-slate-800 mb-1">Tidak Ada Data</p>
                                        <p class="text-sm font-medium text-slate-500">Tidak ada data cabang untuk periode
                                            atau filter yang dipilih.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
