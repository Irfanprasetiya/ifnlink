@extends('layouts.frontend.app')

@section('container')
    <!-- Data Laporan Section -->
    <section class="safe-bottom pb-24 mt-2 px-1 sm:px-2">
        <div class="max-w-7xl mx-auto space-y-4">

            {{-- Header & Tombol Rekap --}}
            <div
                class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4">

                {{-- Efek Glow --}}
                <div
                    class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
                </div>

                <div class="relative z-10 flex items-center gap-3.5">
                    <div
                        class="w-10 h-10 sm:w-11 sm:h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Laporan Harian</h1>
                        <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5">Laporan transaksi per bank/kas
                        </p>
                    </div>
                </div>

                <div class="relative z-10 shrink-0 w-full sm:w-auto">
                    <a href="{{ route('laporan-bank.rekap', ['tanggal' => request('tanggal', now()->toDateString())]) }}"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-bold px-5 py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-[0.98]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Lihat Rekapitulasi
                    </a>
                </div>
            </div>

            {{-- Filter Box Modern --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden p-4">
                <form method="GET" action="{{ route('laporan-bank') }}" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="w-full sm:flex-1">
                        <label for="tanggal"
                            class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Pilih
                            Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            value="{{ request('tanggal', \Carbon\Carbon::now()->toDateString()) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                    </div>
                    <div class="w-full sm:w-auto">
                        <button type="submit"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Data List (Mobile: Cards, Desktop: Table) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

                <div class="px-4 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-800 uppercase tracking-wider">Detail Transaksi
                    </h2>
                </div>

                @php
                    $hasData = false;
                    $no = 1;
                @endphp

                <div class="w-full">

                    {{-- VIEW MOBILE: List Card (Tampil di layar kecil) --}}
                    <div class="block lg:hidden divide-y divide-slate-100">
                        @foreach ($transaksis as $trx)
                            @php
                                $isKas = strtolower($trx->bank->nama_bank ?? '') === 'kas';
                                $isTarikTunai =
                                    strtolower($trx->jenis_transaksi->nama_transaksi ?? '') === 'tarik tunai';
                            @endphp

                            @if (!($isKas && $isTarikTunai))
                                @php $hasData = true; @endphp
                                <div class="p-4 active:bg-slate-50 transition-colors">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <span
                                                class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 mb-1 border border-slate-200/60">
                                                {{ $trx->bank->nama_bank ?? '-' }}
                                            </span>
                                            <h3 class="font-bold text-slate-800 text-sm">
                                                {{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}
                                            </h3>
                                            <p class="text-[10px] font-medium text-slate-500 mt-0.5">
                                                {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i:s') }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p
                                                class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-0.5">
                                                Nominal</p>
                                            <p class="font-extrabold text-sm text-blue-600">
                                                Rp {{ number_format($trx->nominal ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="bg-slate-50 rounded-xl p-3 grid grid-cols-2 gap-y-2.5 gap-x-4 text-[11px] mb-2 border border-slate-100">
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
                                            class="col-span-2 pt-2 mt-1 border-t border-slate-200/60 flex justify-between items-center">
                                            <p class="text-slate-500 font-medium">Saldo Kas</p>
                                            <p class="font-bold {{ $isKas ? 'text-emerald-600' : 'text-slate-700' }}">
                                                Rp {{ number_format($trx->saldo_kas ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($trx->keterangan)
                                        <p class="text-[11px] text-slate-500 bg-slate-50 px-3 py-2 rounded-lg italic">
                                            "{{ $trx->keterangan }}"
                                        </p>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- VIEW DESKTOP: Tabel Modern (Tampil di layar besar) --}}
                    <div class="hidden lg:block overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr
                                    class="bg-slate-50/70 border-b border-slate-100 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider text-center">
                                    <th class="px-4 py-3.5 w-12 text-center">No</th>
                                    <th class="px-4 py-3.5 text-left">Waktu Transaksi</th>
                                    <th class="px-4 py-3.5 text-left">Jenis Transaksi</th>
                                    <th class="px-4 py-3.5">Bank</th>
                                    <th class="px-4 py-3.5 text-right">Nominal</th>
                                    <th class="px-4 py-3.5 text-right">Bayar</th>
                                    <th class="px-4 py-3.5 text-right">Saldo Akhir</th>
                                    <th class="px-4 py-3.5 text-right">Saldo Kas</th>
                                    <th class="px-4 py-3.5 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-medium">
                                @foreach ($transaksis as $trx)
                                    @php
                                        $isKas = strtolower($trx->bank->nama_bank ?? '') === 'kas';
                                        $isTarikTunai =
                                            strtolower($trx->jenis_transaksi->nama_transaksi ?? '') === 'tarik tunai';
                                    @endphp

                                    @if (!($isKas && $isTarikTunai))
                                        <tr class="hover:bg-slate-50/80 transition-colors">
                                            <td class="px-4 py-3 text-center text-slate-400 font-bold">{{ $no++ }}
                                            </td>
                                            <td class="px-4 py-3 text-slate-600">
                                                <span
                                                    class="font-semibold text-slate-700">{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('d-m-Y') }}</span>
                                                <span
                                                    class="text-slate-400 ml-1">{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i:s') }}</span>
                                            </td>
                                            <td class="px-4 py-3 font-bold text-slate-800">
                                                {{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <span
                                                    class="inline-block px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/60">
                                                    {{ $trx->bank->nama_bank ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right font-extrabold text-blue-600">Rp
                                                {{ number_format($trx->nominal ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-emerald-600">Rp
                                                {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-indigo-600 bg-indigo-50/30">Rp
                                                {{ number_format($trx->saldo_akhir ?? 0, 0, ',', '.') }}</td>
                                            <td
                                                class="px-4 py-3 text-right font-bold {{ $isKas ? 'text-emerald-700 bg-emerald-50/40' : 'text-slate-600' }}">
                                                Rp {{ number_format($trx->saldo_kas ?? 0, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3 text-slate-500 max-w-[150px] truncate"
                                                title="{{ $trx->keterangan }}">{{ $trx->keterangan ?? '-' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Empty State jika tidak ada data --}}
                    @if (!$hasData)
                        <div class="py-12 flex flex-col items-center justify-center text-slate-500 bg-slate-50/50">
                            <div
                                class="w-16 h-16 bg-white rounded-full shadow-sm border border-slate-100 flex items-center justify-center mb-3">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-slate-600">Belum ada transaksi</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5">
                                Tidak ada data transaksi yang tercatat pada tanggal tersebut.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection
