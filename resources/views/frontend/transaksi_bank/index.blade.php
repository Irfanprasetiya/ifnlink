@extends('layouts.frontend.app')

@section('container')
    <!-- Data Transaksi Section -->
    <section class="safe-bottom pb-24 mt-4 px-4 sm:px-6">

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
                    class="mb-5 bg-amber-50 border border-amber-200/80 p-4 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-xl">⚠️</span>
                        <p class="text-xs font-medium text-amber-800">
                            Limit 10 transaksi/hari tercapai. <br>
                            <span class="font-bold">Upgrade ke PRO</span> untuk unlimited!
                        </p>
                    </div>
                    <a href="{{ route('upgrade') }}"
                        class="shrink-0 bg-amber-600 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-sm active:scale-95 transition-transform">
                        Upgrade
                    </a>
                </div>
            @elseif($maxTransaksi - $todayCount <= 3)
                <div class="mb-5 bg-blue-50 border border-blue-200/80 p-3 rounded-2xl flex items-center gap-3 shadow-sm">
                    <span class="text-blue-500"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></span>
                    <p class="text-xs text-blue-800 font-medium">
                        Sisa {{ $maxTransaksi - $todayCount }} transaksi lagi hari ini.
                        <a href="{{ route('upgrade') }}"
                            class="font-bold underline decoration-blue-400 underline-offset-2">Upgrade ke PRO</a>
                    </p>
                </div>
            @endif
        @endif

        <!-- Header Pilih Bank -->
        <div class="flex flex-row items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100/80">
            <div class="flex items-center gap-3.5">
                {{-- Ikon Bank --}}
                <div
                    class="w-10 h-10 sm:w-11 sm:h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0 border border-blue-100/50 shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                {{-- Teks --}}
                <div>
                    <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">Pilih Bank / Kas</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5">Tentukan rekening untuk mencatat
                        transaksi</p>
                </div>
            </div>

            {{-- Limit Badge (Free Plan) --}}
            @if ($tenant->plan && $tenant->plan->harga == 0)
                <div class="shrink-0 text-right">
                    {{-- Warna badge akan berubah jadi kuning/merah jika limit hampir habis --}}
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-wider border shadow-sm
                {{ $todayCount >= 10 ? 'bg-rose-50 border-rose-200 text-rose-600' : ($todayCount >= 8 ? 'bg-amber-50 border-amber-200 text-amber-600' : 'bg-white border-slate-200 text-slate-600') }}">

                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="mt-0.5">{{ $todayCount }} / 10 Harian</span>
                    </span>
                </div>
            @endif
        </div>

        <!-- Modern List Data -->
        <div class="space-y-3">
            @forelse ($data as $index => $item)
                @php
                    $isKas = strtolower($item['nama']) === 'kas';
                    $saldo = $item['saldo'] ?? 0;
                @endphp

                @if ($isKas)
                    {{-- KAS CARD: Disabled --}}
                    <div
                        class="flex items-center justify-between p-4 rounded-2xl bg-slate-100 border border-slate-200/60 opacity-80 cursor-not-allowed">
                        <div class="flex items-center gap-3.5">
                            <div
                                class="w-11 h-11 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-lg">
                                K</div>
                            <div>
                                <p class="font-bold text-slate-700 flex items-center gap-2">
                                    {{ $item['nama'] }}
                                    <span
                                        class="text-[9px] bg-slate-200 text-slate-600 px-1.5 py-0.5 rounded font-bold uppercase tracking-wider">Default</span>
                                </p>
                                <p class="text-[11px] text-slate-500 font-medium mt-0.5">Saldo Uang Tunai</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-slate-700">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @elseif ($saldo > 0)
                    {{-- SALDO POSITIF: Bisa diklik --}}
                    <a href="{{ route('transaksi_banks.detail', ['bank_id' => $item['id']]) }}"
                        class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] hover:border-blue-300 active:scale-[0.98] active:bg-slate-50 transition-all duration-200 group">
                        <div class="flex items-center gap-3.5">
                            <div
                                class="w-11 h-11 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                {{ substr(str_replace('Bank ', '', $item['nama']), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $item['nama'] }}</p>
                                <p class="text-[11px] text-emerald-600 font-medium mt-0.5 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Tersedia
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="font-bold text-blue-700">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                            </div>
                            <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @elseif ($saldo < 0)
                    {{-- SALDO NEGATIF: Disabled + warning --}}
                    <div
                        class="flex items-center justify-between p-4 rounded-2xl bg-red-50 border border-red-200/60 opacity-80 cursor-not-allowed">
                        <div class="flex items-center gap-3.5">
                            <div
                                class="w-11 h-11 rounded-full bg-red-100 text-red-500 flex items-center justify-center font-bold text-lg">
                                {{ substr(str_replace('Bank ', '', $item['nama']), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-red-700">{{ $item['nama'] }}</p>
                                <p class="text-[11px] text-red-500 font-medium mt-0.5">⚠️ Saldo awal belum diinput</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-red-600">Rp {{ number_format($saldo, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @else
                    {{-- SALDO NOL: Disabled --}}
                    <div
                        class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 opacity-60 cursor-not-allowed">
                        <div class="flex items-center gap-3.5">
                            <div
                                class="w-11 h-11 rounded-full bg-slate-200 text-slate-400 flex items-center justify-center font-bold text-lg">
                                {{ substr(str_replace('Bank ', '', $item['nama']), 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-600">{{ $item['nama'] }}</p>
                                <p class="text-[11px] text-rose-500 font-medium mt-0.5">Saldo Kosong</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-slate-400">Rp 0</p>
                        </div>
                    </div>
                @endif

            @empty
                <div class="bg-white rounded-2xl border border-slate-100 p-8 text-center shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-700 mb-1">Belum Ada Bank</h3>
                    <p class="text-xs text-slate-500">Tidak ada data transaksi atau rekening untuk hari ini.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
