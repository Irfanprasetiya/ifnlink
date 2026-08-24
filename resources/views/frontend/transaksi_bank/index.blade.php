@extends('layouts.frontend.app')

@section('container')
    <!-- Data Transaksi Section -->
    <section class="safe-bottom pb-24 mt-4 sm:mt-6 px-4 sm:px-6">

        {{-- Notifikasi Limit Transaksi --}}
        @php
            $tenant = Auth::user()->tenant;
            $maxTransaksi = $tenant->plan && $tenant->plan->harga == 0 ? 10 : 9999;
            $todayTransaksi = App\Models\TransaksiBank::where('tenant_id', $tenant->id_tenant)
                ->whereDate('waktu_transaksi', now()->toDateString())
                ->count();
            $todayCount = floor($todayTransaksi / 2);
        @endphp

        @if ($tenant->plan && $tenant->plan->harga == 0)
            @if ($todayCount >= $maxTransaksi)
                <div
                    class="mb-5 sm:mb-6 bg-amber-50 border border-amber-200 p-4 sm:p-5 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 shadow-sm">
                    <div class="flex items-start sm:items-center gap-3">
                        <span class="text-xl shrink-0 mt-0.5 sm:mt-0">⚠️</span>
                        <p class="text-[11px] sm:text-sm font-medium text-amber-800 leading-relaxed">
                            Limit 10 transaksi/hari tercapai. <br class="hidden sm:block">
                            <span class="font-extrabold">Upgrade ke PRO</span> untuk transaksi tanpa batas!
                        </p>
                    </div>
                    <a href="{{ route('upgrade') }}"
                        class="shrink-0 bg-amber-600 text-white text-xs sm:text-sm font-bold px-4 py-2 sm:py-2.5 rounded-xl shadow-sm hover:bg-amber-700 active:scale-95 transition-all text-center">
                        Upgrade Sekarang
                    </a>
                </div>
            @elseif($maxTransaksi - $todayCount <= 3)
                <div
                    class="mb-5 sm:mb-6 bg-blue-50 border border-blue-200 p-3.5 sm:p-4 rounded-2xl flex items-start sm:items-center gap-3 shadow-sm">
                    <span class="text-blue-600 shrink-0 mt-0.5 sm:mt-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <p class="text-[11px] sm:text-sm text-blue-800 font-medium leading-relaxed">
                        Sisa <strong>{{ $maxTransaksi - $todayCount }} transaksi</strong> lagi hari ini.
                        <a href="{{ route('upgrade') }}"
                            class="font-extrabold text-blue-700 hover:text-blue-900 underline decoration-blue-400 underline-offset-2 ml-1 transition-colors">Upgrade
                            ke PRO</a>
                    </p>
                </div>
            @endif
        @endif

        <!-- Header Pilih Bank -->
        <div
            class="flex flex-row items-center justify-between gap-3 mb-5 sm:mb-6 pb-4 sm:pb-5 border-b border-slate-100/80">
            <div class="flex items-center gap-3 sm:gap-4">
                {{-- Ikon Bank --}}
                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0 border border-blue-100/50 shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                {{-- Teks --}}
                <div>
                    <h2 class="text-base sm:text-xl font-extrabold text-slate-900 tracking-tight">Pilih Bank / Kas</h2>
                    <p class="text-[11px] sm:text-sm text-slate-500 font-medium mt-0.5 sm:mt-1">Tentukan rekening sumber
                        dana</p>
                </div>
            </div>

            {{-- Limit Badge (Free Plan) --}}
            @if ($tenant->plan && $tenant->plan->harga == 0)
                <div class="shrink-0 text-right">
                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-lg sm:rounded-xl text-[10px] sm:text-[11px] font-extrabold uppercase tracking-wider border shadow-sm
                        {{ $todayCount >= 10 ? 'bg-rose-50 border-rose-200 text-rose-700' : ($todayCount >= 8 ? 'bg-amber-50 border-amber-200 text-amber-700' : 'bg-slate-50 border-slate-200 text-slate-600') }}">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="mt-0.5">{{ $todayCount }} / 10 Harian</span>
                    </span>
                </div>
            @endif
        </div>

        <!-- Modern List Data -->
        <div class="space-y-3 sm:space-y-4">
            @forelse ($data as $index => $item)
                @php
                    $isKas = strtolower($item['nama']) === 'kas';
                    $saldo = $item['saldo'] ?? 0;
                @endphp

                @if ($isKas)
                    {{-- KAS CARD: Disabled --}}
                    <div
                        class="flex items-center justify-between p-4 sm:p-5 rounded-2xl bg-slate-100 border border-slate-200/80 cursor-not-allowed">
                        <div class="flex items-center gap-3.5 sm:gap-4">
                            <div
                                class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-slate-200/80 flex items-center justify-center text-slate-500 font-bold text-lg sm:text-xl shrink-0">
                                K
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-700 text-sm sm:text-base flex items-center gap-2">
                                    {{ $item['nama'] }}
                                    <span
                                        class="text-[9px] sm:text-[10px] bg-slate-200 text-slate-600 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">Default</span>
                                </p>
                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-1">Saldo Uang Tunai (Kasir)
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 ml-2">
                            <p class="font-bold text-slate-700 text-sm sm:text-base whitespace-nowrap">Rp
                                {{ number_format($saldo, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @elseif ($saldo > 0)
                    {{-- SALDO POSITIF: Bisa diklik --}}
                    <a href="{{ route('transaksi_banks.detail', ['bank_id' => $item['id']]) }}"
                        class="flex items-center justify-between p-4 sm:p-5 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 focus:ring-2 focus:ring-blue-500/30 focus:outline-none active:scale-[0.98] active:bg-slate-50 transition-all duration-200 group">
                        <div class="flex items-center gap-3.5 sm:gap-4">
                            <div
                                class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg sm:text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300 shrink-0">
                                {{ substr(str_replace('Bank ', '', $item['nama']), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-800 text-sm sm:text-base">{{ $item['nama'] }}</p>
                                <p
                                    class="text-[11px] sm:text-xs text-emerald-600 font-semibold mt-1 flex items-center gap-1.5">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_4px_rgba(16,185,129,0.5)]"></span>
                                    Tersedia
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 shrink-0 ml-2">
                            <div class="text-right">
                                <p class="font-extrabold text-blue-700 text-sm sm:text-base whitespace-nowrap">Rp
                                    {{ number_format($saldo, 0, ',', '.') }}</p>
                            </div>
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-slate-300 group-hover:text-blue-500 transition-colors shrink-0"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @elseif ($saldo < 0)
                    {{-- SALDO NEGATIF: Disabled + warning --}}
                    <div
                        class="flex items-center justify-between p-4 sm:p-5 rounded-2xl bg-rose-50/50 border border-rose-200/60 cursor-not-allowed">
                        <div class="flex items-center gap-3.5 sm:gap-4">
                            <div
                                class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-lg sm:text-xl shrink-0 border border-rose-200">
                                {{ substr(str_replace('Bank ', '', $item['nama']), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-extrabold text-rose-700 text-sm sm:text-base">{{ $item['nama'] }}</p>
                                <p class="text-[11px] sm:text-xs text-rose-600 font-medium mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    Saldo minus (Cek Mutasi)
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 ml-2">
                            <p class="font-extrabold text-rose-600 text-sm sm:text-base whitespace-nowrap">Rp
                                {{ number_format($saldo, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @else
                    {{-- SALDO NOL: Disabled --}}
                    <div
                        class="flex items-center justify-between p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-100 opacity-70 cursor-not-allowed">
                        <div class="flex items-center gap-3.5 sm:gap-4">
                            <div
                                class="w-11 h-11 sm:w-12 sm:h-12 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold text-lg sm:text-xl shrink-0">
                                {{ substr(str_replace('Bank ', '', $item['nama']), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-extrabold text-slate-600 text-sm sm:text-base">{{ $item['nama'] }}</p>
                                <p class="text-[11px] sm:text-xs text-slate-400 font-medium mt-1 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Saldo Kosong
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0 ml-2">
                            <p class="font-bold text-slate-400 text-sm sm:text-base whitespace-nowrap">Rp 0</p>
                        </div>
                    </div>
                @endif

            @empty
                <div class="bg-white rounded-3xl border border-slate-100 p-8 sm:p-12 text-center shadow-sm">
                    <div
                        class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100 shadow-inner">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="font-extrabold text-slate-800 text-base sm:text-lg mb-1.5">Belum Ada Rekening Bank</h3>
                    <p class="text-[11px] sm:text-sm text-slate-500 font-medium max-w-xs mx-auto">Anda belum menambahkan
                        data rekening bank atau kas di sistem ini.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
