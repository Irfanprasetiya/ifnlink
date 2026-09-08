@extends('layouts.app')

@section('title', 'Buat Stok Opname')

@section('container')
    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- ========== PAGE HEADER ========== --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 p-3.5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <div class="flex items-start sm:items-center gap-3 sm:gap-4 shrink-0">
                <a href="{{ route('data_master.stok_opname.index') }}" class="mt-0.5 sm:mt-0 p-2 sm:p-2.5 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl border border-slate-200 transition-colors active:scale-90">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-base sm:text-xl font-extrabold text-slate-800 tracking-tight flex items-center gap-2">
                        Buat Stok Opname
                    </h1>
                    <p class="text-[11px] sm:text-sm text-slate-500 mt-0.5 sm:mt-1">
                        Hitung fisik barang di cabang, lalu sesuaikan jumlah riilnya
                    </p>
                </div>
            </div>
        </div>

        {{-- ========== ALERTS ========== --}}
        @if (session('error'))
            <div class="flex items-start gap-2.5 sm:gap-3 bg-rose-50 border border-rose-200/80 text-rose-800 px-3 sm:px-4 py-3 sm:py-3.5 rounded-xl text-[11px] sm:text-sm font-medium animate-[fadeIn_0.3s_ease-out]">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ========== MAIN FORM ========== --}}
        <form action="{{ route('data_master.stok_opname.store') }}" method="POST" id="form-opname" onsubmit="return submitOpname(event)">
            @csrf

            <div class="space-y-4 sm:space-y-6">
                
                {{-- 1. INFORMASI OPNAME --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-bold text-slate-800 text-sm">Informasi Opname</h3>
                    </div>
                    
                    <div class="p-4 sm:p-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div class="sm:col-span-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cabang <span class="text-rose-500">*</span></label>
                            <select name="cabang_id" id="cabang-select" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-blue-500/30 text-slate-700 font-medium font-bold transition-all">
                                <option value="" disabled selected>-- Pilih Cabang --</option>
                                @foreach ($cabangs as $cabang)
                                    <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal Opname <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_opname" value="{{ now()->toDateString() }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-blue-500/30 text-slate-700 font-medium font-bold transition-all">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Catatan (Opsional)</label>
                            <textarea name="catatan" rows="2" placeholder="Contoh: Stok opname rutin bulanan" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 sm:py-3 text-sm focus:ring-2 focus:ring-blue-500/30 text-slate-700 transition-all resize-none"></textarea>
                        </div>
                    </div>
                </div>

                {{-- 2. DAFTAR PRODUK (AJAX TARGET) --}}
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm sm:shadow-soft border border-slate-200/80 overflow-hidden">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            <h3 class="font-bold text-slate-800 text-sm">Daftar Produk di Cabang</h3>
                        </div>
                        <p class="text-[10px] text-slate-500 font-medium">Ubah <strong class="text-blue-600">Stok Fisik</strong> jika ada perbedaan</p>
                    </div>

                    <div class="p-4 sm:p-6 bg-slate-50/30">
                        <div id="produk-opname" class="space-y-3">
                            {{-- Placeholder Awal --}}
                            <div class="py-12 text-center flex flex-col items-center bg-white rounded-xl border border-slate-200 border-dashed">
                                <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <p class="font-bold text-slate-500 text-sm">Pilih cabang terlebih dahulu</p>
                                <p class="text-[11px] text-slate-400 mt-1">Daftar produk akan muncul di sini</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="flex justify-end pt-2">
                    <button type="submit" id="btn-simpan" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 sm:py-3 px-8 rounded-xl transition-all shadow-sm active:scale-95 flex items-center justify-center gap-2">
                        <svg id="btn-simpan-spinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span id="btn-simpan-text">Simpan Stok Opname</span>
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- ========== SMART SCROLL ARROW (MOBILE ONLY) ========== --}}
    <button id="smartScrollBtn" class="lg:hidden fixed bottom-24 right-5 z-[40] bg-blue-600/95 backdrop-blur-sm hover:bg-blue-700 text-white w-10 h-10 rounded-full shadow-[0_4px_12px_rgba(37,99,235,0.4)] flex items-center justify-center transition-transform active:scale-90">
        <svg id="scrollIcon" class="w-5 h-5 transition-transform duration-300 ease-in-out" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </button>
    
    <style>
        /* Hilangkan panah spinner bawaan pada input number */
        input[type="number"]::-webkit-inner-spin-button, 
        input[type="number"]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        // ==== AMBIL DAFTAR PRODUK ====
        document.getElementById('cabang-select').addEventListener('change', function() {
            const cabangId = this.value;
            const container = document.getElementById('produk-opname');

            if (!cabangId) {
                container.innerHTML = `
                    <div class="py-12 text-center flex flex-col items-center bg-white rounded-xl border border-slate-200 border-dashed">
                        <svg class="w-12 h-12 mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <p class="font-bold text-slate-500 text-sm">Pilih cabang terlebih dahulu</p>
                    </div>`;
                return;
            }

            // Tampilkan loading state
            container.innerHTML = `
                <div class="py-10 text-center flex flex-col items-center justify-center">
                    <svg class="animate-spin h-8 w-8 text-blue-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <p class="text-xs text-slate-500 font-medium">Memuat data produk...</p>
                </div>
            `;

            fetch(`/stok-opname/produk/${cabangId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        container.innerHTML = `
                            <div class="py-12 text-center flex flex-col items-center bg-white rounded-xl border border-rose-200 border-dashed">
                                <svg class="w-10 h-10 mb-2 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                <p class="font-bold text-rose-500 text-sm">Belum ada produk di cabang ini</p>
                            </div>`;
                        return;
                    }

                    container.innerHTML = data.map((produk, index) => `
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white border border-slate-200 rounded-xl p-3 sm:p-4 gap-3 shadow-sm hover:border-blue-300 transition-colors">
                            <div class="flex items-start gap-3 flex-1 pr-2">
                                <span class="bg-slate-800 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0 mt-0.5">#${index + 1}</span>
                                <div>
                                    <p class="font-bold text-[13px] sm:text-sm text-slate-800 leading-tight mb-1">${produk.voucher.nama_produk}</p>
                                    <p class="text-[10px] sm:text-[11px] font-medium text-slate-500">Stok Sistem: <span class="text-slate-700 font-bold bg-slate-100 px-1.5 py-0.5 rounded ml-0.5">${produk.stok}</span></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100 border-dashed mt-1 sm:mt-0">
                                <input type="hidden" name="items[${index}][voucher_id]" value="${produk.voucher_id}">
                                <label class="text-[10px] sm:text-[11px] font-bold text-slate-500 uppercase tracking-wider shrink-0">Fisik</label>
                                
                                <div class="flex items-center gap-1.5 shrink-0">
                                    <button type="button" onclick="ubahQtyFisik(${index}, -1)" class="w-8 h-8 flex items-center justify-center bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg text-slate-600 font-bold active:scale-90 transition-transform">-</button>
                                    <input type="number" id="fisik-${index}" name="items[${index}][stok_aktual]" value="${produk.stok}" min="0" class="w-16 bg-white border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/30 rounded-lg px-2 py-1.5 text-sm text-center font-bold text-blue-700 m-0 transition-all">
                                    <button type="button" onclick="ubahQtyFisik(${index}, 1)" class="w-8 h-8 flex items-center justify-center bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-blue-600 font-bold active:scale-90 transition-transform">+</button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(err => {
                    container.innerHTML = `
                        <div class="py-10 text-center text-rose-500 text-sm font-bold">
                            Gagal mengambil data produk. Silakan coba lagi.
                        </div>`;
                });
        });

        // ==== FUNGSI TOMBOL QTY ====
        function ubahQtyFisik(index, delta) {
            const input = document.getElementById(`fisik-${index}`);
            let val = parseInt(input.value) || 0;
            let newVal = val + delta;
            if(newVal >= 0) {
                input.value = newVal;
            }
        }

        // ==== TOMBOL SUBMIT LOADING ====
        function submitOpname(event) {
            // Validasi apakah sudah pilih cabang
            const cabang = document.getElementById('cabang-select').value;
            if(!cabang) {
                alert('Silakan pilih cabang terlebih dahulu!');
                event.preventDefault();
                return false;
            }

            const btn = document.getElementById('btn-simpan');
            const spinner = document.getElementById('btn-simpan-spinner');
            const text = document.getElementById('btn-simpan-text');

            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            spinner.classList.remove('hidden');
            text.textContent = 'Menyimpan...';
            return true;
        }

        // ==== SMART SCROLL LOGIC ====
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