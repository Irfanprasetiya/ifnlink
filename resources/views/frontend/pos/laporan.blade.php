@extends('layouts.frontend.app')

@section('container')
    <div class="space-y-4 sm:space-y-6 pb-32 relative">

        {{-- ========== HEADER & FILTER ========== --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-5">

            <div class="flex justify-between items-center lg:block">
                <h2 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Laporan Penjualan</h2>
                <p class="text-[11px] sm:text-sm text-slate-500 sm:mt-1 flex items-center gap-1 sm:gap-1.5">
                    <span class="hidden sm:inline">Periode: </span>
                    <span class="font-bold text-slate-700">
                        {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} -
                        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
                    </span>
                </p>
            </div>

            <div class="flex flex-col sm:flex-row w-full lg:w-auto gap-2 sm:gap-3">
                <form method="GET" class="flex flex-1 sm:flex-none flex-wrap gap-2">
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full sm:w-36 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-2.5 sm:px-3 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    <span class="text-slate-400 self-center text-xs sm:text-sm">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full sm:w-36 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-2.5 sm:px-3 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 sm:px-5 py-1.5 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold transition-all active:scale-95 shrink-0">
                        Filter
                    </button>
                </form>

                <a href="{{ route('pos.laporan.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                    class="w-full sm:w-auto justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold flex items-center gap-1.5 transition-all active:scale-95">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v1a2 2 0 002 2h14a2 2 0 002-2v-1M3 7v1a2 2 0 002 2h14a2 2 0 002-2V7M3 7a2 2 0 012-2h14a2 2 0 012 2v0">
                        </path>
                    </svg>
                    Unduh PDF
                </a>
            </div>
        </div>

        {{-- ========== SUMMARY CARDS ========== --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-5">
            <div
                class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm sm:shadow-soft border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-1 sm:gap-4 text-center sm:text-left">
                <div
                    class="hidden sm:flex w-12 h-12 shrink-0 rounded-full bg-blue-50 items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Transaksi</p>
                    <p class="text-lg sm:text-2xl font-black text-slate-800 leading-none mt-0.5 sm:mt-1">
                        {{ $totalTransaksi }}</p>
                </div>
            </div>

            <div
                class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm sm:shadow-soft border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-1 sm:gap-4 text-center sm:text-left">
                <div
                    class="hidden sm:flex w-12 h-12 shrink-0 rounded-full bg-emerald-50 items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-[9px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Produk Terjual</p>
                    <p class="text-lg sm:text-2xl font-black text-slate-800 leading-none mt-0.5 sm:mt-1">
                        {{ $totalProdukTerjual }}</p>
                </div>
            </div>

            <div
                class="col-span-2 lg:col-span-1 relative overflow-hidden rounded-xl sm:rounded-2xl p-3.5 sm:p-5 shadow-sm sm:shadow-md border border-blue-600 bg-gradient-to-br from-blue-600 to-blue-800 text-white flex items-center justify-center sm:justify-start gap-3 sm:gap-4 text-center sm:text-left">
                <div class="hidden sm:block absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                <div
                    class="hidden sm:flex w-12 h-12 shrink-0 rounded-full bg-white/20 items-center justify-center text-slate-800 backdrop-blur-sm">
                    <span class="font-bold text-lg">Rp</span>
                </div>
                <div class="relative z-10 w-full sm:w-auto">
                    <p class="text-[9px] sm:text-xs font-bold uppercase tracking-wider text-blue-600">Total Penjualan</p>
                    <p class="text-xl sm:text-3xl font-black text-slate-800 leading-none mt-1 sm:mt-1.5 tracking-tight">
                        <span class="sm:hidden text-sm mr-0.5">Rp</span>{{ number_format($totalPenjualan, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ========== TABEL DATA ========== --}}
        <div
            class="bg-white lg:bg-transparent rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center gap-2 bg-slate-50/50">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                <h3 class="font-bold text-slate-800 text-sm">Rincian Transaksi</h3>
            </div>

            {{-- DESKTOP TABLE --}}
            <div class="hidden lg:block overflow-x-auto bg-white">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="bg-white text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-4 py-3.5 text-center w-12">No</th>
                            <th class="px-4 py-3.5">ID Transaksi</th>
                            <th class="px-4 py-3.5">Waktu</th>
                            <th class="px-4 py-3.5">Produk</th>
                            <th class="px-4 py-3.5">Kategori</th>
                            <th class="px-4 py-3.5 text-center">Qty</th>
                            <th class="px-4 py-3.5 text-right">Harga</th>
                            <th class="px-4 py-3.5 text-right">Subtotal</th>
                            <th class="px-4 py-3.5 text-right">Diskon</th>
                            <th class="px-4 py-3.5 text-right">Total Akhir</th>
                            <th class="px-4 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse($penjualans as $p)
                            @foreach ($p->details as $index => $d)
                                <tr
                                    class="hover:bg-slate-50/50 transition-colors {{ $index === 0 ? 'border-t border-slate-200/80 bg-slate-50/30' : '' }}">
                                    <td class="px-4 py-3 text-center text-slate-500">
                                        @if ($loop->first)
                                            <span
                                                class="w-6 h-6 rounded-full bg-white border border-slate-200 flex items-center justify-center text-xs font-bold mx-auto shadow-sm">{{ $noDesktop++ }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs font-bold text-slate-700">
                                        @if ($loop->first)
                                            {{ $p->kode_transaksi ?? 'TRX-' . str_pad($p->id, 5, '0', STR_PAD_LEFT) }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 text-xs font-medium">
                                        @if ($loop->first)
                                            {{ $p->created_at->translatedFormat('d M H:i') }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $d->voucher->nama_produk ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-500 text-xs">
                                        {{ $d->voucher->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center font-semibold text-slate-700">{{ $d->qty }}</td>
                                    <td class="px-4 py-3 text-right text-slate-500">Rp
                                        {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-semibold text-slate-700">Rp
                                        {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-rose-500 font-bold text-xs">
                                        @if ($loop->first)
                                            @if ($p->diskon > 0)
                                                -Rp {{ number_format($p->diskon, 0, ',', '.') }}
                                            @else
                                                <span class="text-slate-300">-</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if ($loop->first)
                                            <span
                                                class="inline-block bg-emerald-50 text-emerald-700 font-extrabold px-2.5 py-1 rounded-md text-[13px] border border-emerald-100 shadow-sm">
                                                Rp {{ number_format($p->total_setelah_diskon, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($loop->first)
                                            <a href="{{ route('pos.struk', $p->kode_transaksi) }}" target="_blank"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                </svg>
                                                Cetak
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-16 text-center">
                                    <p class="font-medium text-slate-500">Belum ada data penjualan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- MOBILE CARDS --}}
            <div class="block lg:hidden bg-slate-50/50 rounded-b-2xl border-x border-b border-slate-200/80 space-y-4 p-3">
                @php $noMobile = 1; @endphp
                @forelse($penjualans as $p)
                    <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm relative">
                        <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span
                                    class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">#{{ $noMobile++ }}</span>
                                <span
                                    class="font-mono text-xs font-bold text-slate-700">{{ $p->kode_transaksi ?? 'TRX-' . str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <span
                                class="text-xs font-medium text-slate-500">{{ $p->created_at->translatedFormat('d M H:i') }}</span>
                        </div>

                        <div class="space-y-3 mb-4">
                            @foreach ($p->details as $d)
                                <div class="flex justify-between items-start text-sm">
                                    <div class="pr-2">
                                        <p class="font-bold text-slate-800 leading-tight">
                                            {{ $d->voucher->nama_produk ?? '-' }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $d->qty }}x @
                                            {{ number_format($d->harga_satuan, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="font-semibold text-slate-700">Rp
                                            {{ number_format($d->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-3 border-t border-dashed border-slate-200 space-y-1">
                            @if ($p->diskon > 0)
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-500">Diskon</span>
                                    <span class="font-medium text-rose-500">-Rp
                                        {{ number_format($p->diskon, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total</span>
                                <span class="text-base font-black text-emerald-600">Rp
                                    {{ number_format($p->total_setelah_diskon, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- ✅ Tombol Cetak Struk Mobile --}}
                        <a href="{{ route('pos.struk', $p->kode_transaksi) }}" target="_blank"
                            class="flex items-center justify-center gap-1.5 mt-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 rounded-lg text-xs transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak Struk
                        </a>
                    </div>
                @empty
                    <div class="py-12 text-center flex flex-col items-center">
                        <p class="font-medium text-slate-500 text-sm">Belum ada data penjualan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SMART SCROLL --}}
    <button id="smartScrollBtn"
        class="lg:hidden fixed bottom-24 right-5 z-[40] bg-blue-600/95 text-white w-10 h-10 rounded-full shadow-lg flex items-center justify-center">
        <svg id="scrollIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3">
            </path>
        </svg>
    </button>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const scrollBtn = document.getElementById('smartScrollBtn');
            const scrollIcon = document.getElementById('scrollIcon');
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
        });
    </script>
@endsection
