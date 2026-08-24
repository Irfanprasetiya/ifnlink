@extends('layouts.frontend.app')

@section('container')
    <!-- Data Laporan Section -->
    <section class="safe-bottom pb-24 mt-2 px-3 sm:px-4 lg:px-6">
        <div class="max-w-7xl mx-auto space-y-4 sm:space-y-5">

            {{-- Header & Tombol Rekap --}}
            <div
                class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                {{-- Efek Glow Latar Belakang --}}
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 sm:w-40 sm:h-40 bg-blue-50 rounded-full blur-2xl opacity-80 pointer-events-none">
                </div>

                <div class="relative z-10 flex items-center gap-3.5 sm:gap-4">
                    <div
                        class="w-11 h-11 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0 border border-blue-100/50 shadow-sm">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Laporan Harian</h1>
                        <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 sm:mt-1">Laporan transaksi per
                            bank/kas</p>
                    </div>
                </div>

                <div class="relative z-10 shrink-0 w-full sm:w-auto">
                    <a href="{{ route('laporan-bank.rekap', ['tanggal' => request('tanggal', now()->toDateString())]) }}"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-3 sm:py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Lihat Rekapitulasi
                    </a>
                </div>
            </div>

            {{-- Filter Box Modern --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5">
                <form method="GET" action="{{ route('laporan-bank') }}"
                    class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-end">
                    <div class="w-full sm:flex-1">
                        <label for="tanggal"
                            class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Pilih
                            Tanggal Laporan</label>
                        {{-- text-base di mobile mencegah auto-zoom iOS --}}
                        <input type="date" name="tanggal" id="tanggal"
                            value="{{ request('tanggal', \Carbon\Carbon::now()->toDateString()) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 sm:py-2.5 text-base sm:text-sm font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer shadow-inner">
                    </div>
                    <div class="w-full sm:w-auto">
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 sm:py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>

            {{-- Data List (Mobile: Cards, Desktop: Table) --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                <div class="px-4 sm:px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Detail Transaksi Harian</h2>
                </div>

                @php
                    $hasData = false;
                    $noMobile = 1; // Counter khusus untuk mobile card
                    $noDesktop = 1; // Counter khusus untuk desktop table
                @endphp

                <div class="w-full">
                    {{-- VIEW MOBILE: List Card --}}
                    <div class="block lg:hidden space-y-3 bg-slate-50/30">
                        @foreach ($transaksis as $trx)
                            @php
                                $isKas = strtolower($trx->bank->nama_bank ?? '') === 'kas';
                                $isTarikTunai =
                                    strtolower($trx->jenis_transaksi->nama_transaksi ?? '') === 'tarik tunai';
                            @endphp

                            @if (!($isKas && $isTarikTunai))
                                @php $hasData = true; @endphp

                                {{-- KARTU TRANSAKSI MOBILE --}}
                                <div
                                    class="p-3.5 bg-white border border-slate-200 rounded-xl shadow-[0_2px_8px_-4px_rgba(0,0,0,0.05)] active:bg-slate-50 transition-colors relative">

                                    {{-- Card Header: Nomor, Badge Bank, Jam --}}
                                    <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2.5">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-[10px] font-extrabold border border-slate-200/60 shadow-sm shrink-0">
                                                {{ $noMobile++ }}
                                            </span>
                                            <span
                                                class="inline-block px-2.5 py-1 rounded-md text-[9px] font-extrabold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100 shadow-sm truncate max-w-[120px]">
                                                {{ $trx->bank->nama_bank ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-1 text-slate-400">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-[10px] font-bold">
                                                {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}</p>
                                        </div>
                                    </div>

                                    {{-- Card Body: Jenis & Nominal Utama --}}
                                    <div class="flex justify-between items-center mb-3">
                                        <div class="pr-3">
                                            <h3 class="font-extrabold text-slate-800 text-sm leading-tight">
                                                {{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}
                                            </h3>
                                        </div>
                                        <div class="text-right shrink-0">
                                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">
                                                Nominal</p>
                                            <p class="font-extrabold text-sm sm:text-base text-blue-600 whitespace-nowrap">
                                                Rp {{ number_format($trx->nominal ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Card Footer: Grid Detail Keuangan --}}
                                    <div
                                        class="bg-slate-50/80 rounded-lg p-3 grid grid-cols-2 gap-y-2.5 gap-x-3 text-[11px] border border-slate-100 shadow-inner">
                                        <div>
                                            <p class="text-slate-500 font-medium mb-0.5">Total Bayar</p>
                                            <p class="font-bold text-emerald-600">Rp
                                                {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 font-medium mb-0.5">Saldo Akhir Bank</p>
                                            <p class="font-bold text-indigo-600">Rp
                                                {{ number_format($trx->saldo_akhir ?? 0, 0, ',', '.') }}</p>
                                        </div>

                                        <div
                                            class="col-span-2 pt-2 mt-0.5 border-t border-slate-200/80 flex justify-between items-center">
                                            <p class="text-slate-500 font-medium flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                                Saldo Kas
                                            </p>
                                            <p class="font-extrabold {{ $isKas ? 'text-emerald-600' : 'text-slate-700' }}">
                                                Rp {{ number_format($trx->saldo_kas ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Keterangan (Opsional) --}}
                                    @if ($trx->keterangan)
                                        <div
                                            class="mt-2.5 bg-amber-50/50 border border-amber-100 px-3 py-2 rounded-lg flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 text-amber-500 mt-0.5 shrink-0" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-[10px] text-slate-600 italic leading-relaxed">
                                                {{ $trx->keterangan }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- VIEW DESKTOP: Tabel Modern --}}
                    <div class="hidden lg:block overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr
                                    class="bg-slate-50/70 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-wider text-center">
                                    <th class="px-5 py-4 w-12 text-center">No</th>
                                    <th class="px-5 py-4 text-left">Waktu Transaksi</th>
                                    <th class="px-5 py-4 text-left">Jenis Transaksi</th>
                                    <th class="px-5 py-4">Bank</th>
                                    <th class="px-5 py-4 text-right">Nominal</th>
                                    <th class="px-5 py-4 text-right">Bayar</th>
                                    <th class="px-5 py-4 text-right">Saldo Akhir</th>
                                    <th class="px-5 py-4 text-right">Saldo Kas</th>
                                    <th class="px-5 py-4 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm font-medium">
                                @foreach ($transaksis as $trx)
                                    @php
                                        $isKas = strtolower($trx->bank->nama_bank ?? '') === 'kas';
                                        $isTarikTunai =
                                            strtolower($trx->jenis_transaksi->nama_transaksi ?? '') === 'tarik tunai';
                                    @endphp

                                    @if (!($isKas && $isTarikTunai))
                                        @php $hasData = true; @endphp
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="px-5 py-3.5 text-center text-slate-400 font-bold">
                                                {{ $noDesktop++ }}</td>
                                            <td class="px-5 py-3.5 text-slate-600">
                                                <span
                                                    class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('d/m/Y') }}</span>
                                                <span
                                                    class="text-slate-400 ml-1.5 text-xs">{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}</span>
                                            </td>
                                            <td class="px-5 py-3.5 font-extrabold text-slate-800">
                                                {{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}</td>
                                            <td class="px-5 py-3.5 text-center">
                                                <span
                                                    class="inline-block px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                                    {{ $trx->bank->nama_bank ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-5 py-3.5 text-right font-extrabold text-blue-600">Rp
                                                {{ number_format($trx->nominal ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-5 py-3.5 text-right font-bold text-emerald-600">Rp
                                                {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-5 py-3.5 text-right font-bold text-indigo-600 bg-indigo-50/30">Rp
                                                {{ number_format($trx->saldo_akhir ?? 0, 0, ',', '.') }}</td>
                                            <td
                                                class="px-5 py-3.5 text-right font-bold {{ $isKas ? 'text-emerald-700 bg-emerald-50/40' : 'text-slate-600' }}">
                                                Rp {{ number_format($trx->saldo_kas ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-5 py-3.5 text-slate-500 max-w-[150px] truncate italic text-xs"
                                                title="{{ $trx->keterangan }}">
                                                {{ $trx->keterangan ?? '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Empty State jika tidak ada data --}}
                    @if (!$hasData)
                        <div
                            class="py-16 sm:py-20 flex flex-col items-center justify-center text-slate-500 bg-slate-50/50">
                            <div
                                class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-full shadow-sm border border-slate-200 flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-sm sm:text-base font-extrabold text-slate-700 mb-1">Belum Ada Transaksi</p>
                            <p class="text-xs font-medium text-slate-500 text-center max-w-xs px-4">
                                Tidak ada data transaksi yang tercatat pada tanggal laporan yang dipilih.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection
