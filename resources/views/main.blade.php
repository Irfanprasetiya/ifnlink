@extends('layouts.frontend.app')

@section('container')
    <!-- Data Transaksi Section -->
    <section class="safe-bottom pb-24 mt-2 px-1 sm:px-2">
        <section class="mb-10">

            {{-- Header --}}
            <div class="mb-5">
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight">
                    Ringkasan Hari Ini
                </h3>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Aktivitas transaksi Toko Anda</p>
            </div>

            {{-- Kalkulasi Data (Tetap sama) --}}
            @php
                // Hitung transaksi hari ini (kecuali jenis tertentu)
                $todayTrx = App\Models\TransaksiBank::where('user_id', Auth::id())
                    ->whereDate('waktu_transaksi', now()->toDateString())
                    ->where(function ($q) {
                        $q->whereHas('bank', fn($q) => $q->where('nama_bank', '!=', 'Kas'));
                        $q->orWhere(function ($q2) {
                            $q2->whereHas('bank', fn($q) => $q->where('nama_bank', 'Kas'))->whereHas(
                                'jenis_transaksi',
                                fn($q) => $q->whereNotIn('nama_transaksi', [
                                    'Transfer',
                                    'Tarik Tunai',
                                    'Numpang Transfer',
                                ]),
                            );
                        });
                    })
                    ->count();

                // Hitung transaksi kemarin
                $yesterdayTrx = App\Models\TransaksiBank::where('user_id', Auth::id())
                    ->whereDate('waktu_transaksi', now()->subDay()->toDateString())
                    ->whereNotIn('jenis_transaksi_id', [2, 3, 4, 5, 6])
                    ->count();

                // Bandingkan
                $trxDiff = $todayTrx - $yesterdayTrx;
                $trxUp = $trxDiff > 0;
                $trxDown = $trxDiff < 0;
            @endphp

            {{-- Grid 1: Transaksi & Perbandingan --}}
            <div class="grid grid-cols-2 gap-3 mb-4">
                {{-- Card Transaksi Hari Ini --}}
                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-16 h-16 bg-blue-50 rounded-full blur-xl opacity-60"></div>
                    <p class="text-xs font-semibold text-slate-500 mb-3 relative z-10">Total Transaksi</p>
                    <div class="flex items-end justify-between relative z-10">
                        <p class="text-2xl sm:text-3xl font-extrabold text-slate-800 leading-none">
                            {{ $todayTrx }}
                        </p>
                        @if ($todayTrx > 0)
                            <span
                                class="text-[10px] font-bold uppercase tracking-wider text-blue-500 bg-blue-50 px-2 py-0.5 rounded-md">Trx</span>
                        @endif
                    </div>
                </div>

                {{-- Card Perbandingan Kemarin --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 flex flex-col justify-between">
                    <p class="text-xs font-semibold text-slate-500 mb-3">vs Kemarin</p>
                    <div class="flex items-end justify-between">
                        <div class="flex items-center gap-1.5">
                            <p
                                class="text-2xl sm:text-3xl font-extrabold leading-none {{ $trxUp ? 'text-emerald-600' : ($trxDown ? 'text-rose-600' : 'text-slate-800') }}">
                                {{ $yesterdayTrx > 0 ? $yesterdayTrx : '-' }}
                            </p>
                            @if ($trxUp)
                                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            @elseif($trxDown)
                                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" />
                                </svg>
                            @elseif($yesterdayTrx > 0)
                                <span class="text-sm text-slate-400 font-bold">=</span>
                            @endif
                        </div>
                        @if ($trxDiff != 0 && $yesterdayTrx > 0)
                            <span class="text-xs font-bold {{ $trxUp ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $trxUp ? '+' : '' }}{{ $trxDiff }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kartu Utama: Saldo Kas (Hero Card) -->
            <a href="{{ route('transaksi-bank') }}"
                class="block bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-emerald-100 p-4 sm:p-5 mb-4 hover:border-emerald-300 active:scale-[0.98] active:bg-emerald-50/30 transition-all duration-200 group relative overflow-hidden">

                {{-- Efek cahaya latar (hanya estetika) --}}
                <div
                    class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-emerald-50 rounded-full blur-2xl opacity-70 pointer-events-none">
                </div>

                <div class="relative z-10 flex items-center justify-between">
                    <div class="flex items-center gap-3.5 sm:gap-4 w-full">
                        <div
                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-emerald-100/80 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <span class="material-symbols-outlined text-2xl sm:text-3xl">account_balance_wallet</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p
                                class="text-[11px] sm:text-xs font-semibold text-emerald-600/80 uppercase tracking-wider mb-0.5">
                                Saldo Kas Utama</p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-extrabold text-slate-800 truncate">
                                Rp {{ number_format($saldoAkhirKas, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="shrink-0 ml-2 w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:bg-emerald-100 transition-colors">
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>

            <!-- Grid 2: Kartu Pendukung (Modern Pill UI) -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">

                <!-- Penambahan Kas -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-3.5 sm:p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div
                            class="w-6 h-6 sm:w-7 sm:h-7 rounded-md bg-emerald-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-emerald-600 text-[16px]">arrow_downward</span>
                        </div>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Kas Masuk
                        </p>
                    </div>
                    <p class="text-sm sm:text-base font-bold text-slate-800 truncate">
                        Rp {{ number_format($tambahanKas, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Pengurangan Kas -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-3.5 sm:p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-md bg-rose-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-rose-600 text-[16px]">arrow_upward</span>
                        </div>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Kas Keluar
                        </p>
                    </div>
                    <p class="text-sm sm:text-base font-bold text-slate-800 truncate">
                        Rp {{ number_format($penguranganKas, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Tarik Tunai -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-3.5 sm:p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-md bg-amber-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-amber-600 text-[16px]">payments</span>
                        </div>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Tarik Tunai
                        </p>
                    </div>
                    <p class="text-sm sm:text-base font-bold text-slate-800 truncate">
                        Rp {{ number_format($totalTarikTunai, 0, ',', '.') }}
                    </p>
                </div>

                <!-- Transfer -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-3.5 sm:p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-md bg-blue-50 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-blue-600 text-[16px]">send_money</span>
                        </div>
                        <p class="text-[11px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Transfer</p>
                    </div>
                    <p class="text-sm sm:text-base font-bold text-slate-800 truncate">
                        Rp {{ number_format($totalTransfer, 0, ',', '.') }}
                    </p>
                </div>

            </div>
        </section>
    </section>
@endsection
