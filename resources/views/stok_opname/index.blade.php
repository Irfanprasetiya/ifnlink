@extends('layouts.app')

@section('title', 'Stok Opname')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER & ACTIONS ========== --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Stok Opname</h1>
                    <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 sm:mt-1">Pencatatan koreksi stok produk per
                        cabang</p>
                </div>
            </div>

            <a href="{{ route('data_master.stok_opname.create') }}"
                class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold flex items-center gap-1.5 transition-all active:scale-95 shadow-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Opname
            </a>
        </div>

        {{-- ========== ALERTS ========== --}}
        @if (session('success'))
            <div
                class="flex items-start gap-2.5 sm:gap-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-3 sm:px-4 py-3 sm:py-3.5 rounded-xl text-[11px] sm:text-sm font-medium animate-[fadeIn_0.3s_ease-out]">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ========== DATA CONTAINER ========== --}}
        <div
            class="bg-white lg:bg-transparent rounded-xl sm:rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">

            {{-- Header Tabel (Hanya tampil di Desktop) --}}
            <div class="hidden lg:flex p-4 border-b border-slate-100 items-center gap-2 bg-slate-50/50">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                <h3 class="font-bold text-slate-800 text-sm">Riwayat Stok Opname</h3>
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
                            <th class="px-5 py-4">Kode Opname</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Cabang</th>
                            <th class="px-5 py-4">User</th>
                            <th class="px-5 py-4">Catatan</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse($opnames as $opname)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md border border-slate-200/60">{{ $opname->kode_opname }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ $opname->tanggal_opname->format('d M Y') }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $opname->cabang->nama_cabang ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $opname->user->name ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs truncate max-w-[200px]"
                                    title="{{ $opname->catatan }}">{{ $opname->catatan ?: '-' }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="{{ route('data_master.stok_opname.show', $opname->kode_opname) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-blue-50 border border-blue-200 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors shadow-sm active:scale-95">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <p class="font-medium text-slate-600">Belum ada data stok opname</p>
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
                @forelse($opnames as $opname)
                    <!-- Single Card -->
                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative">

                        {{-- Header Card: No & Kode Opname --}}
                        <div class="flex items-center justify-between mb-2.5 pb-2.5 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span
                                    class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">#{{ $noMobile++ }}</span>
                                <span
                                    class="font-mono text-[11px] font-bold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200/60">{{ $opname->kode_opname }}</span>
                            </div>

                            {{-- Tanggal --}}
                            <span class="text-[10px] font-bold text-slate-500 flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $opname->tanggal_opname->format('d M Y') }}
                            </span>
                        </div>

                        {{-- Body: Cabang & User --}}
                        <div class="mb-3 space-y-1">
                            <h3 class="font-bold text-slate-800 text-sm leading-tight">
                                {{ $opname->cabang->nama_cabang ?? '-' }}</h3>
                            <div class="flex items-center text-[10px] text-slate-500 gap-1.5">
                                <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Petugas: <span
                                        class="font-semibold text-slate-700">{{ $opname->user->name ?? '-' }}</span></span>
                            </div>
                        </div>

                        {{-- Catatan --}}
                        @if ($opname->catatan)
                            <div class="mb-3 bg-slate-50/80 p-2 rounded-lg border border-slate-100">
                                <p class="text-[10px] text-slate-600 line-clamp-2 italic">"{{ $opname->catatan }}"</p>
                            </div>
                        @endif

                        {{-- Footer Card: Button Detail --}}
                        <div class="pt-2 border-t border-dashed border-slate-200">
                            <a href="{{ route('data_master.stok_opname.show', $opname->kode_opname) }}"
                                class="w-full flex items-center justify-center gap-1.5 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors shadow-sm active:scale-95 border border-blue-200">
                                <span>Lihat Rincian Opname</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="font-medium text-slate-500 text-xs">Belum ada data stok opname</p>
                    </div>
                @endforelse
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

    {{-- Style Animation & Script Scroll --}}
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

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
