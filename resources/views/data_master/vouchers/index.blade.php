@extends('layouts.app')

@section('title', 'Data Voucher')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER & ACTIONS ========== --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4 sm:gap-5">
            
            <div class="flex justify-between items-center lg:block">
                <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight">Data Master Voucher</h1>
                <p class="text-[11px] sm:text-sm text-slate-500 sm:mt-1 hidden sm:block">Kelola harga beli, harga jual, dan kategori voucher</p>
            </div>

            <div class="flex flex-col sm:flex-row w-full lg:w-auto gap-2.5 sm:gap-3">
                {{-- Form Filter & Pencarian --}}
                <form method="GET" class="flex flex-col sm:flex-row flex-1 sm:flex-none gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama voucher..." 
                        class="w-full sm:w-48 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-3 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                    
                    <div class="flex gap-2">
                        <select name="kategori_id" class="w-full sm:w-40 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-2.5 sm:px-3 py-2 sm:py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/30">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold transition-all active:scale-95 shrink-0">
                            Filter
                        </button>
                    </div>
                </form>

                {{-- Tombol Tambah --}}
                <button data-modal-target="create-modal" data-modal-toggle="create-modal" class="w-full sm:w-auto justify-center bg-blue-600 hover:bg-blue-700 text-white px-3.5 sm:px-5 py-2.5 sm:py-2.5 rounded-lg sm:rounded-xl text-xs sm:text-sm font-bold flex items-center gap-1.5 transition-all active:scale-95 shadow-sm mt-1 sm:mt-0">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Voucher
                </button>
            </div>
        </div>

        {{-- ========== ALERTS ========== --}}
        @if (session('success'))
            <div class="flex items-start gap-2.5 sm:gap-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-3 sm:px-4 py-3 sm:py-3.5 rounded-xl text-[11px] sm:text-sm font-medium animate-[fadeIn_0.3s_ease-out]">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-2.5 sm:gap-3 bg-rose-50 border border-rose-200/80 text-rose-800 px-3 sm:px-4 py-3 sm:py-3.5 rounded-xl text-[11px] sm:text-sm font-medium animate-[fadeIn_0.3s_ease-out]">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ========== DATA CONTAINER ========== --}}
        <div class="bg-white lg:bg-transparent rounded-xl sm:rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">
            
            {{-- Header Tabel (Hanya tampil di Desktop) --}}
            <div class="hidden lg:flex p-4 border-b border-slate-100 items-center gap-2 bg-slate-50/50">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Voucher</h3>
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
                            <th class="px-5 py-4 text-right">Harga Beli</th>
                            <th class="px-5 py-4 text-right">Harga Jual</th>
                            <th class="px-5 py-4">Keterangan</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $noDesktop = 1; @endphp
                        @forelse ($vouchers as $voucher)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-500 font-medium">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $voucher->nama_produk }}</td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md text-xs border border-slate-200">{{ $voucher->kategori->nama_kategori ?? '-' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right font-medium text-slate-600">Rp {{ number_format($voucher->harga_beli, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5 text-right font-bold text-emerald-600">Rp {{ number_format($voucher->harga_jual, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs truncate max-w-[200px]">{{ $voucher->keterangan ?: '-' }}</td>
                                <td class="px-5 py-3.5 flex justify-center gap-2">
                                    <button data-modal-toggle="edit-modal-{{ $voucher->id }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-amber-300 text-amber-600 rounded-lg text-xs font-bold hover:bg-amber-50 transition-colors shadow-sm active:scale-95">
                                        Edit
                                    </button>
                                    <form action="{{ route('data_master.vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus voucher ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-rose-300 text-rose-600 rounded-lg text-xs font-bold hover:bg-rose-50 transition-colors shadow-sm active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <p class="font-medium text-slate-500">Belum ada data voucher</p>
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
                @forelse ($vouchers as $voucher)
                    <!-- Single Card -->
                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative">
                        
                        {{-- Header Card: No Urut & Aksi --}}
                        <div class="flex items-start justify-between mb-2.5 pb-2.5 border-b border-slate-100">
                            <div class="flex items-start gap-2.5 pr-2">
                                <span class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 mt-0.5">#{{ $noMobile++ }}</span>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-sm leading-tight mb-1">{{ $voucher->nama_produk }}</h3>
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-500 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-200">{{ $voucher->kategori->nama_kategori ?? '-' }}</span>
                                </div>
                            </div>
                            
                            {{-- Action Mini (Mobile) --}}
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button data-modal-toggle="edit-modal-{{ $voucher->id }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-md border border-amber-200 active:scale-90 transition-transform">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <form action="{{ route('data_master.vouchers.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')" class="m-0 p-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-md border border-rose-200 active:scale-90 transition-transform">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Body: Ket --}}
                        @if($voucher->keterangan)
                            <div class="mb-3">
                                <p class="text-[10px] text-slate-500 line-clamp-2">{{ $voucher->keterangan }}</p>
                            </div>
                        @endif

                        {{-- Footer Card: Harga Beli & Harga Jual --}}
                        <div class="flex items-center justify-between pt-2 border-t border-dashed border-slate-200">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Harga Beli</p>
                                <p class="font-semibold text-slate-600 text-xs">Rp {{ number_format($voucher->harga_beli, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Harga Jual</p>
                                <p class="font-black text-emerald-600 text-sm">Rp {{ number_format($voucher->harga_jual, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <p class="font-medium text-slate-500 text-xs">Belum ada data voucher</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{-- AREA MODAL (Disimpan di luar DOM utama agar bersih) --}}
    {{-- ========================================================= --}}

    {{-- Modal Tambah Voucher --}}
    <form method="POST" action="{{ route('data_master.vouchers.store') }}">
        @csrf
        <div id="create-modal" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden transform transition-all my-8">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-base font-extrabold text-slate-800">Tambah Voucher Baru</h3>
                    <button type="button" data-modal-toggle="create-modal" class="text-slate-400 hover:text-rose-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-5 space-y-4 max-h-[60vh] overflow-y-auto">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Produk</label>
                        <input type="text" name="nama_produk" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Harga Beli</label>
                            <input type="number" step="0.01" name="harga_beli" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Harga Jual</label>
                            <input type="number" step="0.01" name="harga_jual" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kategori</label>
                        <select name="kategori_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Keterangan (Opsional)</label>
                        <textarea name="keterangan" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30"></textarea>
                    </div>
                </div>
                <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                    <button type="button" data-modal-toggle="create-modal" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    {{-- Render Semua Modal Edit --}}
    @foreach ($vouchers as $voucher)
        <form method="POST" action="{{ route('data_master.vouchers.update', $voucher->id) }}">
            @csrf @method('PUT')
            <div id="edit-modal-{{ $voucher->id }}" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
                <div class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden transform transition-all my-8">
                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-base font-extrabold text-slate-800">Edit Voucher</h3>
                        <button type="button" data-modal-toggle="edit-modal-{{ $voucher->id }}" class="text-slate-400 hover:text-rose-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-5 space-y-4 max-h-[60vh] overflow-y-auto">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Nama Produk</label>
                            <input type="text" name="nama_produk" value="{{ $voucher->nama_produk }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Harga Beli</label>
                                <input type="number" step="0.01" name="harga_beli" value="{{ $voucher->harga_beli }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Harga Jual</label>
                                <input type="number" step="0.01" name="harga_jual" value="{{ $voucher->harga_jual }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Kategori</label>
                            <select name="kategori_id" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ $voucher->kategori_id == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30">{{ $voucher->keterangan }}</textarea>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end gap-2">
                        <button type="button" data-modal-toggle="edit-modal-{{ $voucher->id }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold transition-colors shadow-sm">Update Data</button>
                    </div>
                </div>
            </div>
        </form>
    @endforeach


    {{-- ========== SMART SCROLL ARROW (MOBILE ONLY) ========== --}}
    <button id="smartScrollBtn" class="lg:hidden fixed bottom-24 right-5 z-[40] bg-blue-600/95 backdrop-blur-sm hover:bg-blue-700 text-white w-10 h-10 rounded-full shadow-[0_4px_12px_rgba(37,99,235,0.4)] flex items-center justify-center transition-transform active:scale-90">
        <svg id="scrollIcon" class="w-5 h-5 transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
        </svg>
    </button>

    {{-- Style Animation & Script Scroll --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
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