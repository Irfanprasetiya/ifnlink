@extends('layouts.app')

@section('title', 'Riwayat Stok')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER & FILTER ========== --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col xl:flex-row xl:items-center justify-between gap-4 sm:gap-5">

            <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                <div
                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Stok</h1>
                    <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 sm:mt-1">Log pergerakan barang (Masuk, Keluar,
                        Opname, Retur)</p>
                </div>
            </div>

            <div class="w-full xl:w-auto">
                <form method="GET"
                    class="flex flex-col sm:flex-row flex-wrap gap-2.5 sm:gap-3 justify-start xl:justify-end w-full">

                    {{-- Filter Tanggal --}}
                    <div class="flex gap-2 w-full sm:w-auto items-center">
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="flex-1 sm:w-36 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-2.5 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                        <span class="text-slate-400 font-bold text-xs sm:text-sm">s/d</span>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="flex-1 sm:w-36 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-2.5 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    </div>

                    {{-- Filter Dropdown & Tombol --}}
                    <div class="flex gap-2 w-full sm:w-auto">
                        <select name="jenis"
                            class="flex-1 sm:w-32 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-2.5 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            <option value="">Semua Jenis</option>
                            <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk</option>
                            <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                            <option value="opname" {{ request('jenis') == 'opname' ? 'selected' : '' }}>Opname</option>
                            <option value="retur" {{ request('jenis') == 'retur' ? 'selected' : '' }}>Retur</option>
                            <option value="hapus" {{ request('jenis') == 'hapus' ? 'selected' : '' }}>Hapus</option>
                        </select>

                        <select name="cabang_id"
                            class="flex-1 sm:w-40 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-2.5 py-1.5 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}"
                                    {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold transition-all active:scale-95 shrink-0 flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 hidden sm:block" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== DATA CONTAINER ========== --}}
        <div
            class="bg-white lg:bg-transparent rounded-xl sm:rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">

            {{-- Header Tabel (Hanya tampil di Desktop) --}}
            <div class="hidden lg:flex p-4 border-b border-slate-100 items-center justify-between gap-2 bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    <h3 class="font-bold text-slate-800 text-sm">Log Transaksi Stok</h3>
                </div>
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
                            <th class="px-5 py-4">Waktu</th>
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4">Cabang</th>
                            <th class="px-5 py-4 text-center">Jenis</th>
                            <th class="px-5 py-4 text-center">Qty</th>
                            <th class="px-5 py-4 min-w-[200px]">Keterangan</th>
                            <th class="px-5 py-4">User</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = ($histories->currentPage() - 1) * $histories->perPage() + 1; @endphp
                        @forelse($histories as $h)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs">
                                    {{ $h->created_at->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $h->voucher->nama_produk ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">{{ $h->cabang->nama_cabang ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($h->jenis == 'masuk')
                                        <span
                                            class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 uppercase">Masuk</span>
                                    @elseif($h->jenis == 'keluar')
                                        <span
                                            class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 uppercase">Keluar</span>
                                    @elseif($h->jenis == 'opname')
                                        <span
                                            class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200/80 uppercase">Opname</span>
                                    @elseif($h->jenis == 'retur')
                                        <span
                                            class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 uppercase">Retur</span>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80 uppercase">Hapus</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span
                                        class="font-black {{ in_array($h->jenis, ['masuk', 'opname', 'retur']) ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ in_array($h->jenis, ['masuk', 'opname', 'retur']) ? '+' : '-' }}{{ $h->qty }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500 truncate max-w-[250px]"
                                    title="{{ $h->keterangan }}">{{ $h->keterangan ?: '-' }}</td>
                                <td class="px-5 py-3.5 text-xs font-semibold text-slate-600">{{ $h->user->name ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <p class="font-medium text-slate-600">Belum ada riwayat pergerakan stok</p>
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
                @php $noMobile = ($histories->currentPage() - 1) * $histories->perPage() + 1; @endphp
                @forelse($histories as $h)
                    <!-- Single Card -->
                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative">

                        {{-- Header Card: No, Waktu & Badge --}}
                        <div class="flex items-start justify-between mb-2.5 pb-2.5 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span
                                    class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">#{{ $noMobile++ }}</span>
                                <span class="text-[10px] text-slate-500 font-medium flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $h->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>

                            {{-- Status Badge (Mobile) --}}
                            @if ($h->jenis == 'masuk')
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 uppercase">Masuk</span>
                            @elseif($h->jenis == 'keluar')
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-rose-50 text-rose-700 border border-rose-200/80 uppercase">Keluar</span>
                            @elseif($h->jenis == 'opname')
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200/80 uppercase">Opname</span>
                            @elseif($h->jenis == 'retur')
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 uppercase">Retur</span>
                            @else
                                <span
                                    class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 text-slate-700 border border-slate-200/80 uppercase">Hapus</span>
                            @endif
                        </div>

                        {{-- Body: Nama Produk & Cabang/User --}}
                        <div class="mb-3 space-y-1">
                            <h3 class="font-bold text-slate-800 text-sm leading-tight">
                                {{ $h->voucher->nama_produk ?? '-' }}</h3>
                            <div class="flex flex-wrap items-center text-[10px] text-slate-500 gap-x-2 gap-y-1">
                                <span
                                    class="bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200/60 font-bold uppercase tracking-wider">{{ $h->cabang->nama_cabang ?? '-' }}</span>
                                <span>•</span>
                                <span>Oleh: <span
                                        class="font-semibold text-slate-700">{{ $h->user->name ?? '-' }}</span></span>
                            </div>
                        </div>

                        {{-- Keterangan (Opsional) --}}
                        @if ($h->keterangan)
                            <div class="mb-3 bg-slate-50/80 p-2 rounded-lg border border-slate-100">
                                <p class="text-[10px] text-slate-600 line-clamp-2 italic">"{{ $h->keterangan }}"</p>
                            </div>
                        @endif

                        {{-- Footer Card: Qty --}}
                        <div class="flex items-center justify-between pt-2.5 border-t border-dashed border-slate-200">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Kuantitas
                                Stok</span>
                            <span
                                class="font-black text-lg {{ in_array($h->jenis, ['masuk', 'opname', 'retur']) ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ in_array($h->jenis, ['masuk', 'opname', 'retur']) ? '+' : '-' }}{{ $h->qty }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="font-medium text-slate-500 text-xs">Belum ada riwayat pergerakan stok</p>
                    </div>
                @endforelse
            </div>

            {{-- Area Paginasi --}}
            @if ($histories->hasPages())
                <div class="p-4 border-t border-slate-100 bg-white sm:bg-slate-50/50">
                    {{ $histories->links() }}
                </div>
            @endif
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
