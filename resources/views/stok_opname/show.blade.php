@extends('layouts.app')

@section('title', 'Detail Stok Opname')

@section('container')

    {{-- ========== LOGIKA PEMROSESAN DATA ========== --}}
    @php
        $totalNominalPlus = 0;
        $totalNominalMinus = 0;
        $processedDetails = [];

        foreach ($details as $detail) {
            preg_match('/Stok lama: (\d+)/', $detail->keterangan, $lama);
            preg_match('/Stok baru: (\d+)/', $detail->keterangan, $baru);

            $stokLama = (int) ($lama[1] ?? 0);
            $stokBaru = (int) ($baru[1] ?? 0);
            $selisih = $stokBaru - $stokLama;
            $harga = $detail->voucher->harga_jual ?? 0;
            $nominal = $selisih * $harga;

            if ($nominal > 0) {
                $totalNominalPlus += $nominal;
            } else {
                $totalNominalMinus += abs($nominal);
            }

            $processedDetails[] = (object) [
                'original' => $detail,
                'stokLama' => $stokLama,
                'stokBaru' => $stokBaru,
                'selisih' => $selisih,
                'harga' => $harga,
                'nominal' => $nominal,
            ];
        }
    @endphp

    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER & ACTIONS ========== --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div class="flex items-start sm:items-center gap-3 sm:gap-4 shrink-0">
                <a href="{{ route('data_master.stok_opname.index') }}"
                    class="mt-0.5 sm:mt-0 p-2 sm:p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl border border-slate-200 transition-colors active:scale-90">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                        {{ $opname->kode_opname }}
                    </h1>
                    <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 sm:mt-1 font-medium flex items-center gap-1.5">
                        <span
                            class="bg-slate-100 px-1.5 py-0.5 rounded text-slate-600">{{ $opname->cabang->nama_cabang ?? 'Pusat' }}</span>
                        <span>•</span>
                        <span>{{ $opname->tanggal_opname->format('d M Y') }}</span>
                    </p>
                </div>
            </div>

            <a href="{{ route('data_master.stok_opname.pdf', $opname->kode_opname) }}"
                class="w-full sm:w-auto justify-center bg-rose-600 hover:bg-rose-700 text-white px-4 py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 transition-all active:scale-95 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Unduh PDF
            </a>
        </div>

        {{-- ========== DATA CONTAINER ========== --}}
        <div
            class="bg-white lg:bg-transparent rounded-xl sm:rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">

            {{-- Header Tabel (Hanya tampil di Desktop) --}}
            <div class="hidden lg:flex p-4 border-b border-slate-100 items-center gap-2 bg-slate-50/50">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                    </path>
                </svg>
                <h3 class="font-bold text-slate-800 text-sm">Rincian Hasil Opname</h3>
            </div>

            {{-- ========================================== --}}
            {{-- 1. TAMPILAN DESKTOP (TABEL) --}}
            {{-- ========================================== --}}
            <div class="hidden lg:block overflow-x-auto bg-white">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead
                        class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 text-center w-12">No</th>
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4 text-right">Harga Jual</th>
                            <th class="px-5 py-4 text-center">Stok Sistem</th>
                            <th class="px-5 py-4 text-center">Stok Fisik</th>
                            <th class="px-5 py-4 text-center">Selisih</th>
                            <th class="px-5 py-4 text-right pr-6">Nominal Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse($processedDetails as $item)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    {{ $item->original->voucher->nama_produk ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-right font-medium text-slate-600">Rp
                                    {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5 text-center text-slate-500">{{ $item->stokLama }}</td>
                                <td class="px-5 py-3.5 text-center font-black text-blue-600">{{ $item->stokBaru }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($item->selisih > 0)
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">+{{ $item->selisih }}</span>
                                    @elseif($item->selisih < 0)
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">{{ $item->selisih }}</span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-500 border border-slate-200">0</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-bold pr-6">
                                    @if ($item->nominal > 0)
                                        <span class="text-emerald-600">+Rp
                                            {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                    @elseif($item->nominal < 0)
                                        <span class="text-rose-600">-Rp
                                            {{ number_format(abs($item->nominal), 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-400">Rp 0</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <p class="font-medium text-slate-500">Tidak ada detail opname</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    {{-- Table Footer (Total) Desktop --}}
                    @if ($totalNominalPlus > 0 || $totalNominalMinus > 0)
                        <tfoot class="bg-slate-50/80 border-t-2 border-slate-200">
                            <tr>
                                <td colspan="6"
                                    class="px-5 py-4 text-right font-extrabold text-[11px] uppercase tracking-wider text-slate-600">
                                    Total Nilai Selisih</td>
                                <td class="px-5 py-4 text-right pr-6 space-y-1">
                                    @if ($totalNominalPlus > 0)
                                        <div class="text-emerald-600 font-black text-sm">+Rp
                                            {{ number_format($totalNominalPlus, 0, ',', '.') }}</div>
                                    @endif
                                    @if ($totalNominalMinus > 0)
                                        <div class="text-rose-600 font-black text-sm">-Rp
                                            {{ number_format($totalNominalMinus, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>

            {{-- ========================================== --}}
            {{-- 2. TAMPILAN MOBILE (CLEAN CARDS) --}}
            {{-- ========================================== --}}
            <div class="block lg:hidden bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-200/80 space-y-3">
                @php $noMobile = 1; @endphp
                @forelse($processedDetails as $item)
                    <!-- Single Detail Card -->
                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative">

                        {{-- Card Header: Nama Produk --}}
                        <div class="flex items-start gap-2.5 mb-3 border-b border-slate-100 pb-2.5">
                            <span
                                class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 mt-0.5">#{{ $noMobile++ }}</span>
                            <div>
                                <h3 class="font-bold text-slate-800 text-sm leading-tight mb-1">
                                    {{ $item->original->voucher->nama_produk ?? '-' }}</h3>
                                <p class="text-[10px] text-slate-500 font-medium font-mono">Harga: Rp
                                    {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        {{-- Card Body: Stok Perbandingan --}}
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div class="bg-slate-50 p-2 rounded-lg border border-slate-100 text-center">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Sistem</p>
                                <p class="font-semibold text-slate-600 text-sm">{{ $item->stokLama }}</p>
                            </div>
                            <div class="bg-blue-50 p-2 rounded-lg border border-blue-100 text-center">
                                <p class="text-[9px] font-bold text-blue-400 uppercase tracking-wider mb-0.5">Fisik</p>
                                <p class="font-black text-blue-700 text-sm">{{ $item->stokBaru }}</p>
                            </div>
                        </div>

                        {{-- Card Footer: Selisih & Nominal --}}
                        <div class="flex items-center justify-between pt-2 border-t border-dashed border-slate-200">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Selisih</p>
                                @if ($item->selisih > 0)
                                    <span class="font-black text-emerald-600 text-xs">+{{ $item->selisih }} Pcs</span>
                                @elseif($item->selisih < 0)
                                    <span class="font-black text-rose-600 text-xs">{{ $item->selisih }} Pcs</span>
                                @else
                                    <span class="font-bold text-slate-400 text-xs">0 Pcs</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nominal</p>
                                @if ($item->nominal > 0)
                                    <span class="font-black text-emerald-600 text-sm">+Rp
                                        {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                @elseif($item->nominal < 0)
                                    <span class="font-black text-rose-600 text-sm">-Rp
                                        {{ number_format(abs($item->nominal), 0, ',', '.') }}</span>
                                @else
                                    <span class="font-bold text-slate-400 text-sm">Rp 0</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="font-medium text-slate-500 text-xs">Tidak ada detail opname</p>
                    </div>
                @endforelse

                {{-- Summary / Footer Total (Mobile) --}}
                @if ($totalNominalPlus > 0 || $totalNominalMinus > 0)
                    <div class="bg-slate-800 rounded-xl p-4 mt-2 shadow-sm text-white">
                        <p
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 border-b border-slate-700 pb-2">
                            Total Nilai Selisih</p>
                        <div class="space-y-1">
                            @if ($totalNominalPlus > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-slate-300">Surplus (+)</span>
                                    <span class="font-black text-emerald-400">+Rp
                                        {{ number_format($totalNominalPlus, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if ($totalNominalMinus > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-slate-300">Defisit (-)</span>
                                    <span class="font-black text-rose-400">-Rp
                                        {{ number_format($totalNominalMinus, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- ========== SMART SCROLL ARROW (MOBILE ONLY) ========== --}}
    <button id="smartScrollBtn"
        class="lg:hidden fixed bottom-24 right-5 z-[40] bg-blue-600/95 backdrop-blur-sm hover:bg-blue-700 text-white w-10 h-10 rounded-full shadow-[0_4px_12px_rgba(37,99,235,0.4)] flex items-center justify-center transition-transform active:scale-90">
        <svg id="scrollIcon" class="w-5 h-5 transition-transform duration-300 ease-in-out" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3">
            </path>
        </svg>
    </button>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const scrollBtn = document.getElementById('smartScrollBtn');
            const scrollIcon = document.getElementById('scrollIcon');

            if (scrollBtn && scrollIcon) {
                let isPointingDown = true;

                window.addEventListener('scroll', () => {
                    if (window.scrollY > 300) {
                        scrollIcon.style.transform = 'rotate(180deg)';
                        isPointingDown = false;
                    } else {
                        scrollIcon.style.transform = 'rotate(0deg)';
                        isPointingDown = true;
                    }
                });

                scrollBtn.addEventListener('click', () => {
                    if (isPointingDown) {
                        window.scrollTo({
                            top: document.body.scrollHeight,
                            behavior: 'smooth'
                        });
                    } else {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                });
            }
        });
    </script>
@endsection
