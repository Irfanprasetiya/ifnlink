@extends('layouts.frontend.app')

@section('container')
    <div class="space-y-4 sm:space-y-6 pb-32 relative">

        {{-- ========== PAGE HEADER ========== --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Retur</h2>
                <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 sm:mt-1">Pantau status pengajuan retur Anda</p>
            </div>
            <a href="{{ route('retur.create') }}"
                class="w-full sm:w-auto text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold transition-colors active:scale-95 shadow-sm">
                + Ajukan Retur Baru
            </a>
        </div>

        {{-- ========== SUCCESS ALERT ========== --}}
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

            {{-- ========================================== --}}
            {{-- 1. TAMPILAN DESKTOP (TABEL) --}}
            {{-- ========================================== --}}
            <div class="hidden lg:block overflow-x-auto bg-white">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead
                        class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 text-center w-12">No</th>
                            <th class="px-5 py-4">Kode Retur</th>
                            <th class="px-5 py-4">Rincian Produk</th>
                            <th class="px-5 py-4 text-right">Total Refund</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4">Tanggal Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse($returs as $r)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">
                                    {{ $noDesktop++ }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-1 rounded-md border border-slate-200/60">
                                        {{ $r->kode_retur }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="space-y-1.5">
                                        @foreach ($r->details as $d)
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="font-semibold text-slate-800">{{ $d->voucher->nama_produk ?? '-' }}</span>
                                                <span
                                                    class="text-[11px] font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded">x{{ $d->qty }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-right font-bold text-slate-900">
                                    Rp {{ number_format($r->total_refund, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($r->status == 'pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> PENDING
                                        </span>
                                    @elseif($r->status == 'approved')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> APPROVED
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> REJECTED
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs font-medium">
                                    {{ $r->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <p class="font-medium text-slate-600">Belum ada riwayat retur</p>
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
                @forelse($returs as $r)
                    <!-- Single Card -->
                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative">

                        {{-- Header Card: No Urut, ID & Status --}}
                        <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span
                                    class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">#{{ $noMobile++ }}</span>
                                <span class="font-mono text-[11px] font-bold text-slate-700">{{ $r->kode_retur }}</span>
                            </div>

                            @if ($r->status == 'pending')
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 uppercase tracking-wide">Pending</span>
                            @elseif($r->status == 'approved')
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 uppercase tracking-wide">Approved</span>
                            @else
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 uppercase tracking-wide">Rejected</span>
                            @endif
                        </div>

                        {{-- Daftar Produk dalam Retur Ini --}}
                        <div class="space-y-2 mb-3.5">
                            @foreach ($r->details as $d)
                                <div class="flex justify-between items-start text-xs">
                                    <p class="font-bold text-slate-800 leading-tight pr-2">
                                        {{ $d->voucher->nama_produk ?? '-' }}</p>
                                    <p
                                        class="font-semibold text-slate-500 shrink-0 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">
                                        {{ $d->qty }} Pcs
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Footer Card: Tanggal & Total Refund --}}
                        <div class="flex items-end justify-between pt-3 border-t border-dashed border-slate-200">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Waktu
                                    Pengajuan</p>
                                <p class="text-[11px] font-medium text-slate-600">
                                    {{ $r->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Refund
                                </p>
                                <p class="font-black text-emerald-600 text-sm">Rp
                                    {{ number_format($r->total_refund, 0, ',', '.') }}</p>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="font-medium text-slate-500 text-xs">Belum ada riwayat retur</p>
                    </div>
                @endforelse
            </div>

            {{-- ========== PAGINATION ========== --}}
            @if ($returs->hasPages())
                <div
                    class="px-4 md:px-5 py-3 md:py-4 border-t border-slate-200 bg-white lg:bg-slate-50/50 rounded-b-xl lg:rounded-none mt-2 lg:mt-0 shadow-sm lg:shadow-none">
                    {{ $returs->links() }}
                </div>
            @endif
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

    {{-- Style Animation --}}
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
                    // Cek posisi scroll
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
