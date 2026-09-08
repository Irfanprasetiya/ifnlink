@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER ========== --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex items-center gap-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Laporan Stok Produk</h1>
                <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 sm:mt-1">Pantau ketersediaan stok voucher di semua cabang</p>
            </div>
        </div>

        {{-- ========== SUMMARY CARDS ========== --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2.5 sm:gap-5">
            <!-- Card 1: Total Produk -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm sm:shadow-soft border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-1 sm:gap-4 text-center sm:text-left">
                <div>
                    <p class="text-[9px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Total Jenis Produk</p>
                    <p class="text-lg sm:text-2xl font-black text-slate-800 mt-0.5 sm:mt-1">{{ $totalProduk }}</p>
                </div>
            </div>

            <!-- Card 2: Total Stok Keseluruhan -->
            <div class="bg-white rounded-xl sm:rounded-2xl p-3 sm:p-5 shadow-sm sm:shadow-soft border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-center sm:justify-start gap-1 sm:gap-4 text-center sm:text-left">
                <div>
                    <p class="text-[9px] sm:text-xs font-bold uppercase tracking-wider text-slate-500">Total Stok Keseluruhan</p>
                    <p class="text-lg sm:text-2xl font-black text-blue-600 mt-0.5 sm:mt-1">{{ $totalStok }}</p>
                </div>
            </div>

            <!-- Card 3: Stok Menipis (Peringatan) -->
            <div class="col-span-2 lg:col-span-1 relative overflow-hidden rounded-xl sm:rounded-2xl p-3.5 sm:p-5 shadow-sm sm:shadow-md border border-rose-200 bg-rose-50 flex items-center justify-center sm:justify-start gap-3 sm:gap-4 text-center sm:text-left">
                <div class="hidden sm:flex w-10 h-10 shrink-0 rounded-full bg-rose-100 items-center justify-center text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div class="w-full sm:w-auto">
                    <p class="text-[9px] sm:text-xs font-bold uppercase tracking-wider text-rose-500">Stok Menipis (≤5)</p>
                    <p class="text-xl sm:text-3xl font-black text-rose-600 mt-0.5 sm:mt-1 tracking-tight">{{ $stokMenipis }}</p>
                </div>
            </div>
        </div>

        {{-- ========== DATA CONTAINER ========== --}}
        <div class="bg-white lg:bg-transparent rounded-xl sm:rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">
            
            {{-- Header Tabel (Hanya tampil di Desktop) --}}
            <div class="hidden lg:flex p-4 border-b border-slate-100 items-center gap-2 bg-slate-50/50">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                <h3 class="font-bold text-slate-800 text-sm">Rincian Stok per Cabang</h3>
            </div>

            {{-- ========================================== --}}
            {{-- 1. TAMPILAN DESKTOP (TABEL) --}}
            {{-- ========================================== --}}
            <div class="hidden lg:block overflow-x-auto bg-white">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 text-center w-12">No</th>
                            <th class="px-5 py-4">Nama Produk</th>
                            <th class="px-5 py-4">Kategori</th>
                            <th class="px-5 py-4">Cabang Pengelola</th>
                            <th class="px-5 py-4 text-center">Stok Saat Ini</th>
                            <th class="px-5 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse($stoks as $stok)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $stok->voucher->nama_produk ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs border border-slate-200">{{ $stok->voucher->kategori->nama_kategori ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-700">{{ $stok->cabang->nama_cabang ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-md border border-blue-100">{{ $stok->stok }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($stok->stok <= 5)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span> Menipis
                                        </span>
                                    @elseif($stok->stok <= 10)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Waspada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Aman
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        <p class="font-medium text-slate-600">Data stok produk kosong</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ========================================== --}}
            {{-- 2. TAMPILAN MOBILE (CLEAN CARDS) --}}
            {{-- ========================================== --}}
            <div class="block lg:hidden bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-200/80 space-y-3">
                @php $noMobile = 1; @endphp
                @forelse($stoks as $stok)
                    <!-- Single Card -->
                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative">
                        
                        {{-- Header Card: No Urut & Cabang --}}
                        <div class="flex items-center justify-between mb-2.5 pb-2.5 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">#{{ $noMobile++ }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $stok->cabang->nama_cabang ?? '-' }}</span>
                            </div>
                            
                            {{-- Status Badge (Mobile) --}}
                            @if ($stok->stok <= 5)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 uppercase tracking-wide flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Menipis
                                </span>
                            @elseif($stok->stok <= 10)
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 uppercase tracking-wide">Waspada</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 uppercase tracking-wide">Aman</span>
                            @endif
                        </div>

                        {{-- Body: Nama Produk & Kategori --}}
                        <div class="mb-3">
                            <h3 class="font-bold text-slate-800 text-sm leading-tight mb-1">{{ $stok->voucher->nama_produk ?? '-' }}</h3>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">{{ $stok->voucher->kategori->nama_kategori ?? '-' }}</span>
                        </div>

                        {{-- Footer Card: Jumlah Stok --}}
                        <div class="flex items-center justify-between pt-2.5 border-t border-dashed border-slate-200">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Stok Tersedia</span>
                            <span class="font-black text-blue-600 text-base bg-blue-50 px-3 py-0.5 rounded-lg border border-blue-100">{{ $stok->stok }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <p class="font-medium text-slate-500 text-xs">Belum ada stok produk</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ========== SMART SCROLL ARROW (MOBILE ONLY) ========== --}}
    <button id="smartScrollBtn" class="lg:hidden fixed bottom-24 right-5 z-[40] bg-blue-600/95 backdrop-blur-sm hover:bg-blue-700 text-white w-10 h-10 rounded-full shadow-[0_4px_12px_rgba(37,99,235,0.4)] flex items-center justify-center transition-transform active:scale-90">
        <svg id="scrollIcon" class="w-5 h-5 transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </button>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const scrollBtn = document.getElementById('smartScrollBtn');
            const scrollIcon = document.getElementById('scrollIcon');
            
            if(scrollBtn && scrollIcon) {
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
                        window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
                    } else {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
@endsection