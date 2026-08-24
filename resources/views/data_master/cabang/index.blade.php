@extends('layouts.app')

@section('title', 'Daftar Cabang')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-4 sm:space-y-6 pb-12 mt-3 sm:mt-5">

        {{-- Logika Limit Cabang (TIDAK DIUBAH SAMA SEKALI) --}}
        @php
            $tenant = Auth::user()->tenant;
            $maxCabang = $tenant->plan && $tenant->plan->harga == 0 ? 1 : 999;
            // Hitung cabang SELAIN Gudang
            $currentCabang = $tenant->cabang()->where('nama_cabang', '!=', 'Gudang')->count();
            $canAddCabang = $currentCabang < $maxCabang;
        @endphp

        {{-- Notifikasi Limit Cabang (Mobile Friendly) --}}
        @if (!$canAddCabang)
            <div
                class="bg-amber-50 border border-amber-200 text-amber-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm relative overflow-hidden">
                <div class="flex items-start sm:items-center gap-2.5 sm:gap-3 relative z-10">
                    <div class="p-1.5 sm:p-2 bg-amber-100/80 rounded-lg text-amber-600 shrink-0 mt-0.5 sm:mt-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <p class="text-[11px] sm:text-sm font-medium leading-relaxed">
                        Paket Gratis hanya mengizinkan 1 cabang tambahan. <strong class="hidden sm:inline">Upgrade ke PRO
                            untuk cabang tanpa batas.</strong>
                    </p>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white text-[11px] sm:text-sm font-bold px-3 py-2 sm:px-4 sm:py-2 rounded-lg transition-colors relative z-10 active:scale-95">
                    Upgrade Sekarang <span class="ml-1 sm:hidden">→</span>
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0 && $canAddCabang)
            <div
                class="bg-blue-50/80 border border-blue-100 text-blue-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl flex items-start sm:items-center gap-2.5 sm:gap-3 shadow-sm">
                <div class="p-1.5 sm:p-2 bg-blue-100/80 rounded-lg text-blue-600 shrink-0 mt-0.5 sm:mt-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-[11px] sm:text-sm font-medium leading-relaxed">
                    Info Paket Gratis: Maksimal 1 cabang tambahan <span class="text-blue-600">(Cabang
                        <strong>Gudang</strong> bawaan sistem tidak dihitung)</span>.
                </p>
            </div>
        @endif

        {{-- Notifikasi Sukses / Error --}}
        @if (session('success'))
            <div id="alert-success"
                class="flex items-center justify-between bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-[11px] sm:text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" class="text-emerald-500 hover:text-emerald-800 font-bold p-1"
                    data-dismiss-target="#alert-success">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error"
                class="flex items-center justify-between bg-rose-50 border border-rose-200 text-rose-800 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl shadow-sm">
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 text-rose-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-[11px] sm:text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" class="text-rose-500 hover:text-rose-800 font-bold p-1"
                    data-dismiss-target="#alert-error">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Header & Tombol Tambah --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 w-full flex items-center justify-between">
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        <span
                            class="p-1.5 sm:p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0 text-sm sm:text-base">🏢</span>
                        Daftar Cabang
                    </h1>
                    <p class="text-[10px] sm:text-sm text-slate-500 mt-1 font-medium hidden sm:block">
                        Kelola data lokasi cabang dan gudang untuk operasional bisnis Anda.
                    </p>
                </div>

                <div class="shrink-0">
                    @if ($canAddCabang)
                        <button data-modal-target="crud-modal" data-modal-toggle="crud-modal" type="button"
                            class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95 w-full sm:w-auto">
                            <span>+</span> <span class="hidden sm:inline">Tambah Cabang</span>
                        </button>
                    @else
                        <button disabled type="button" title="Kuota cabang penuh"
                            class="inline-flex items-center justify-center gap-1.5 bg-slate-100 text-slate-400 cursor-not-allowed font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm border border-slate-200 w-full sm:w-auto">
                            <span>🔒</span> <span class="hidden sm:inline">Limit Tercapai</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm sm:text-base font-bold text-slate-800">Data Lokasi Cabang</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center w-12 hidden sm:table-cell">No</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5">Info Cabang</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 hidden md:table-cell">Alamat</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 hidden lg:table-cell">Keterangan</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center w-24 sm:w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @php $no = 1; @endphp
                        @forelse ($cabangs as $cabang)
                            @php $isGudang = strtolower($cabang->nama_cabang) === 'gudang'; @endphp

                            <tr class="hover:bg-slate-50/70 transition-colors {{ $isGudang ? 'bg-amber-50/20' : '' }}">
                                {{-- Hidden on mobile --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-center hidden sm:table-cell">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-[10px] sm:text-xs font-semibold text-slate-500 border border-slate-200">
                                        {{ $no++ }}
                                    </span>
                                </td>

                                {{-- Info Cabang: Di mobile menampilkan Nama & Alamat sekaligus --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="font-bold {{ $isGudang ? 'text-amber-700' : 'text-slate-900' }}">
                                        {{ $cabang->nama_cabang }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5 md:hidden truncate max-w-[200px]">
                                        {{ $cabang->alamat_cabang ?: 'Tidak ada detail alamat' }}
                                    </div>
                                </td>

                                {{-- Hidden on mobile/tablet small --}}
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-slate-600 hidden md:table-cell truncate max-w-[250px]">
                                    {{ $cabang->alamat_cabang ?: '-' }}
                                </td>

                                {{-- Hidden on everything except large screens --}}
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-slate-500 hidden lg:table-cell truncate max-w-[200px]">
                                    {{ $cabang->keterangan ?: '-' }}
                                </td>

                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                        @if ($isGudang)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[9px] sm:text-[11px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wider border border-amber-200/60">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
                                                </svg>
                                                Pusat
                                            </span>
                                        @elseif(Auth::user()->role === 'super_admin')
                                            <span class="text-[10px] sm:text-xs text-slate-400 italic">Read Only</span>
                                        @else
                                            {{-- Tombol Edit --}}
                                            <button type="button" data-modal-target="edit-modal-{{ $cabang->id }}"
                                                data-modal-toggle="edit-modal-{{ $cabang->id }}" title="Edit Cabang"
                                                class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg sm:rounded-xl transition active:scale-95">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('cabang.destroy', $cabang->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Hapus Cabang"
                                                    class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg sm:rounded-xl transition active:scale-95">
                                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- MODAL EDIT CABANG (Untuk non-Gudang) --}}
                            @if (!$isGudang)
                                <div id="edit-modal-{{ $cabang->id }}" tabindex="-1"
                                    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
                                    <div
                                        class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                                        <div
                                            class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Edit Data Cabang</h3>
                                            <button type="button" data-modal-toggle="edit-modal-{{ $cabang->id }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <form method="POST" action="{{ route('cabang.update', $cabang->id) }}"
                                            class="p-5 sm:p-6 space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label
                                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                                    Cabang</label>
                                                <input type="text" name="nama_cabang"
                                                    value="{{ $cabang->nama_cabang }}" required
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Alamat
                                                    Lengkap</label>
                                                <input type="text" name="alamat_cabang"
                                                    value="{{ $cabang->alamat_cabang }}" required
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Keterangan
                                                    <span
                                                        class="text-slate-400 font-normal capitalize">(Opsional)</span></label>
                                                <input type="text" name="keterangan"
                                                    value="{{ $cabang->keterangan }}"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                            </div>

                                            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4">
                                                <button type="button" data-modal-toggle="edit-modal-{{ $cabang->id }}"
                                                    class="w-full sm:w-auto px-4 py-3 sm:py-2.5 rounded-xl text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors active:scale-95 text-center">Batal</button>
                                                <button type="submit"
                                                    class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 sm:py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="text-center py-12 text-slate-400 text-xs sm:text-sm font-medium">
                                    Belum ada data cabang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH CABANG UTAMA --}}
    <form method="POST" action="{{ route('data_master.cabang.store') }}">
        @csrf
        <div id="crud-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
            <div
                class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200 my-auto">
                <div
                    class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Tambah Cabang Baru</h3>
                    <button type="button" data-modal-toggle="crud-modal"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-5 sm:p-6 space-y-4">
                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                            Cabang</label>
                        <input type="text" name="nama_cabang" value="{{ old('nama_cabang') }}" required
                            placeholder="Contoh: Cabang Jakarta"
                            class="w-full bg-slate-50 border {{ $errors->has('nama_cabang') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        <x-input-error :messages="$errors->get('nama_cabang')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Alamat
                            Lengkap</label>
                        <input type="text" name="alamat_cabang" value="{{ old('alamat_cabang') }}" required
                            placeholder="Contoh: Jl. Sudirman No. 123"
                            class="w-full bg-slate-50 border {{ $errors->has('alamat_cabang') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        <x-input-error :messages="$errors->get('alamat_cabang')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Keterangan
                            <span class="text-slate-400 font-normal capitalize">(Opsional)</span></label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                            placeholder="Tulis keterangan operasional"
                            class="w-full bg-slate-50 border {{ $errors->has('keterangan') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                    </div>

                    <div class="pt-4 pb-2">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">
                            Simpan Cabang Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
