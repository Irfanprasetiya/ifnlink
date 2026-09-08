@extends('layouts.app')

@section('title', 'Stok Menipis')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER ========== --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border-l-4 border-rose-500 border-y border-r border-slate-200/80 p-3.5 sm:p-6 flex items-center gap-3 sm:gap-4">
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Peringatan Stok Menipis</h1>
                <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 sm:mt-1">Daftar produk yang memiliki sisa stok 5 atau
                    kurang</p>
            </div>
        </div>

        {{-- ========== DATA CONTAINER ========== --}}
        <div
            class="bg-white lg:bg-transparent rounded-xl sm:rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">

            {{-- Header Tabel (Hanya tampil di Desktop) --}}
            <div class="hidden lg:flex p-4 border-b border-slate-100 items-center gap-2 bg-slate-50/50">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                    </path>
                </svg>
                <h3 class="font-bold text-slate-800 text-sm">Rincian Produk Perlu Restock</h3>
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
                            <th class="px-5 py-4">Nama Produk</th>
                            <th class="px-5 py-4">Cabang Pengelola</th>
                            <th class="px-5 py-4 text-center">Sisa Stok</th>
                            <th class="px-5 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse($stokMenipis as $stok)
                            <tr class="hover:bg-rose-50/30 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $stok->voucher->nama_produk ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">{{ $stok->cabang->nama_cabang ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="font-black text-rose-600 bg-rose-50 px-3 py-1.5 rounded-md border border-rose-100 shadow-sm">{{ $stok->stok }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 uppercase">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-pulse"></span>
                                        Menipis
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-emerald-500">
                                        <div
                                            class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <p class="font-bold text-emerald-600">Semua stok produk dalam kondisi aman.</p>
                                        <p class="text-xs text-emerald-500/70 mt-1">Tidak ada produk yang menipis saat ini.
                                        </p>
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
                @forelse($stokMenipis as $stok)
                    <!-- Single Card -->
                    <div class="bg-white border border-rose-200 rounded-xl p-3.5 shadow-sm relative overflow-hidden">

                        {{-- Aksen Merah di pinggir card --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>

                        {{-- Header Card: No & Cabang --}}
                        <div class="flex items-center justify-between mb-2.5 pb-2.5 border-b border-slate-100 pl-2">
                            <div class="flex items-center gap-2">
                                <span
                                    class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">#{{ $noMobile++ }}</span>
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $stok->cabang->nama_cabang ?? '-' }}</span>
                            </div>

                            {{-- Status Badge (Mobile) --}}
                            <span
                                class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 uppercase tracking-wide flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span> Menipis
                            </span>
                        </div>

                        {{-- Body: Nama Produk --}}
                        <div class="mb-3 pl-2">
                            <h3 class="font-bold text-slate-800 text-sm leading-tight">
                                {{ $stok->voucher->nama_produk ?? '-' }}</h3>
                        </div>

                        {{-- Footer Card: Jumlah Stok --}}
                        <div class="flex items-center justify-between pt-2.5 border-t border-dashed border-slate-200 pl-2">
                            <span class="text-[9px] font-bold text-rose-400 uppercase tracking-wider">Sisa Stok</span>
                            <span
                                class="font-black text-rose-600 text-base bg-rose-50 px-3 py-0.5 rounded-lg border border-rose-100 shadow-sm">{{ $stok->stok }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <div
                            class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <p class="font-bold text-emerald-600 text-sm">Semua stok produk aman.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Tombol ke Barang Masuk --}}
        <div class="flex gap-3">
            <a href="{{ route('barang_masuk.index') }}"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-3.5 rounded-xl transition shadow-sm flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Input Barang Masuk
            </a>
        </div>
    </div>

    {{-- ========== SMART SCROLL ARROW (MOBILE ONLY) ========== --}}
    <button id="smartScrollBtn"
        class="lg:hidden fixed bottom-24 right-5 z-[40] bg-blue-600/95 backdrop-blur-sm hover:bg-blue-700 text-white w-10 h-10 rounded-full shadow-[0_4px_12px_rgba(37,99,235,0.4)] flex items-center justify-center transition-transform active:scale-90">
        <svg id="scrollIcon" class="w-5 h-5 transition-transform duration-300 ease-in-out" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
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
