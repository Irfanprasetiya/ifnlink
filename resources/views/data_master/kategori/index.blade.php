@extends('layouts.app')

@section('title', 'Data Kategori')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER & ACTIONS ========== --}}
        <div
            class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div>
                <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Data Kategori</h1>
                <p class="text-[11px] sm:text-sm text-slate-500 sm:mt-1 hidden sm:block">Kelola pengelompokan jenis produk
                    atau voucher</p>
            </div>

            <button data-modal-target="tambah-kategori-modal" data-modal-toggle="tambah-kategori-modal"
                class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold flex items-center gap-1.5 transition-all active:scale-95 shadow-sm">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Kategori
            </button>
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

        @if (session('error'))
            <div
                class="flex items-start gap-2.5 sm:gap-3 bg-rose-50 border border-rose-200/80 text-rose-800 px-3 sm:px-4 py-3 sm:py-3.5 rounded-xl text-[11px] sm:text-sm font-medium animate-[fadeIn_0.3s_ease-out]">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ session('error') }}</span>
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
                <h3 class="font-bold text-slate-800 text-sm">Daftar Kategori</h3>
            </div>

            {{-- ========================================== --}}
            {{-- 1. TAMPILAN DESKTOP (TABEL) --}}
            {{-- ========================================== --}}
            <div class="hidden lg:block overflow-x-auto bg-white">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead
                        class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 text-center w-16">No</th>
                            <th class="px-5 py-4">Nama Kategori</th>
                            <th class="px-5 py-4 text-right pr-8">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse ($kategoris as $kategori)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $kategori->nama_kategori }}</td>
                                <td class="px-5 py-3.5 flex justify-end gap-2 pr-8">
                                    <button data-modal-toggle="edit-modal-{{ $kategori->id }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-amber-300 text-amber-600 rounded-lg text-xs font-bold hover:bg-amber-50 transition-colors shadow-sm active:scale-95">
                                        Edit
                                    </button>
                                    <form action="{{ route('data_master.kategoris.destroy', $kategori->id) }}"
                                        method="POST" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-rose-300 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 transition-colors shadow-sm active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-16 text-center">
                                    <p class="font-medium text-slate-500">Belum ada data kategori</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ========================================== --}}
            {{-- 2. TAMPILAN MOBILE (CLEAN CARDS) --}}
            {{-- ========================================== --}}
            <div class="block lg:hidden bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-200/80 space-y-2">
                @php $noMobile = 1; @endphp
                @forelse ($kategoris as $kategori)
                    <!-- Single Compact Card -->
                    <div
                        class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm flex items-center justify-between">

                        <div class="flex items-center gap-3">
                            <span
                                class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0">#{{ $noMobile++ }}</span>
                            <span class="font-bold text-sm text-slate-800">{{ $kategori->nama_kategori }}</span>
                        </div>

                        {{-- Action Mini (Mobile) --}}
                        <div class="flex items-center gap-1.5 shrink-0">
                            <button data-modal-toggle="edit-modal-{{ $kategori->id }}"
                                class="p-1.5 bg-amber-50 text-amber-600 rounded-md border border-amber-200 active:scale-90 transition-transform">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </button>
                            <form action="{{ route('data_master.kategoris.destroy', $kategori->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus?')" class="m-0 p-0">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 bg-rose-50 text-rose-600 rounded-md border border-rose-200 active:scale-90 transition-transform">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <p class="font-medium text-slate-500 text-xs">Belum ada kategori</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- AREA MODAL (Disimpan di luar DOM utama agar bersih) --}}
    {{-- ========================================================= --}}

    {{-- Modal Tambah --}}
    <form action="{{ route('data_master.kategoris.store') }}" method="POST">
        @csrf
        <div id="tambah-kategori-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden transform transition-all">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-base font-extrabold text-slate-800">Tambah Kategori Baru</h3>
                    <button type="button" data-modal-toggle="tambah-kategori-modal"
                        class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-5">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama
                        Kategori</label>
                    <input type="text" name="nama_kategori" required placeholder="Contoh: Voucher, Elektrik, Minuman"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                </div>
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                    <button type="button" data-modal-toggle="tambah-kategori-modal"
                        class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Render Semua Modal Edit --}}
    @foreach ($kategoris as $kategori)
        <form action="{{ route('data_master.kategoris.update', $kategori->id) }}" method="POST">
            @csrf @method('PUT')
            <div id="edit-modal-{{ $kategori->id }}" tabindex="-1" aria-hidden="true"
                class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden transform transition-all">
                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-base font-extrabold text-slate-800">Edit Kategori</h3>
                        <button type="button" data-modal-toggle="edit-modal-{{ $kategori->id }}"
                            class="text-slate-400 hover:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama
                            Kategori</label>
                        <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                    </div>
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                        <button type="button" data-modal-toggle="edit-modal-{{ $kategori->id }}"
                            class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold transition-colors shadow-sm">Update
                            Data</button>
                    </div>
                </div>
            </div>
        </form>
    @endforeach


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
