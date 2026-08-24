@extends('layouts.app')

@section('title', 'Daftar Akun Pengeluaran')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-4 sm:space-y-6 pb-12 mt-3 sm:mt-5">

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
                            class="p-1.5 sm:p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0 text-sm sm:text-base">💸</span>
                        Daftar Akun Pengeluaran
                    </h1>
                    <p class="text-[10px] sm:text-sm text-slate-500 mt-1 font-medium hidden sm:block">
                        Kelola pos akun pengeluaran kas untuk pencatatan keuangan operasional bisnis.
                    </p>
                </div>

                <div class="shrink-0">
                    <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal" type="button"
                        class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95 w-full sm:w-auto">
                        <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>+</span> <span class="hidden sm:inline">Tambah Akun</span>
                    </button>
                </div>
            </div>
        </div>

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

        {{-- Tabel Data --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm sm:text-base font-bold text-slate-800">Daftar Akun Aktif</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center w-12 hidden sm:table-cell">No</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5">Info Akun</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 hidden md:table-cell">Keterangan</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center w-24 sm:w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @php $no = 1; @endphp
                        @forelse ($akunPengeluarans as $item)
                            @php $isMaster = is_null($item->tenant_id); @endphp

                            <tr class="hover:bg-slate-50/70 transition-colors {{ $isMaster ? 'bg-amber-50/20' : '' }}">
                                {{-- Hidden on mobile --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-center hidden sm:table-cell">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-[10px] sm:text-xs font-semibold text-slate-500 border border-slate-200">
                                        {{ $no++ }}
                                    </span>
                                </td>

                                {{-- Info Akun: Di mobile menampilkan Nama & Keterangan sekaligus --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="font-bold {{ $isMaster ? 'text-amber-700' : 'text-slate-900' }}">
                                        {{ $item->nama_akun }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5 md:hidden truncate max-w-[200px]">
                                        {{ $item->keterangan ?: 'Tidak ada keterangan' }}
                                    </div>
                                </td>

                                {{-- Hidden on mobile/tablet small --}}
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-slate-600 hidden md:table-cell truncate max-w-[300px]">
                                    {{ $item->keterangan ?: '-' }}
                                </td>

                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                        @if ($isMaster)
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[9px] sm:text-[11px] font-bold bg-amber-100 text-amber-700 uppercase tracking-wider border border-amber-200/60">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
                                                </svg>
                                                Sistem
                                            </span>
                                        @else
                                            {{-- Tombol Edit (Icon Only) --}}
                                            <button type="button" data-modal-target="edit-modal-{{ $item->id }}"
                                                data-modal-toggle="edit-modal-{{ $item->id }}" title="Edit Akun"
                                                class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg sm:rounded-xl transition active:scale-95">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus (Icon Only) --}}
                                            <form action="{{ route('data_master.akun_pengeluaran.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Hapus Akun"
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

                            {{-- MODAL EDIT (Untuk non-Master) --}}
                            @if (!$isMaster)
                                <div id="edit-modal-{{ $item->id }}" tabindex="-1"
                                    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
                                    <div
                                        class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                                        <div
                                            class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                            <h3 class="text-base sm:text-lg font-bold text-slate-800">Edit Akun Pengeluaran
                                            </h3>
                                            <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                                class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <form method="POST"
                                            action="{{ route('data_master.akun_pengeluaran.update', $item->id) }}"
                                            class="p-5 sm:p-6 space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label
                                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                                    Akun</label>
                                                <input type="text" name="nama_akun" value="{{ $item->nama_akun }}"
                                                    required
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Keterangan
                                                    <span
                                                        class="text-slate-400 font-normal capitalize">(Opsional)</span></label>
                                                <input type="text" name="keterangan" value="{{ $item->keterangan }}"
                                                    placeholder="Opsional"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                                            </div>

                                            <div class="flex flex-col-reverse sm:flex-row justify-end gap-2.5 pt-4">
                                                <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
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
                                <td colspan="4"
                                    class="text-center py-12 text-slate-400 text-xs sm:text-sm font-medium">
                                    Belum ada data akun pengeluaran terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH AKUN PENGELUARAN --}}
    <form method="POST" action="{{ route('data_master.akun_pengeluaran.store') }}">
        @csrf
        <div id="tambah-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
            <div
                class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200 my-auto">
                <div
                    class="px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Tambah Akun Baru</h3>
                    <button type="button" data-modal-toggle="tambah-modal"
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
                            Akun</label>
                        <input type="text" name="nama_akun" value="{{ old('nama_akun') }}" required
                            placeholder="Contoh: Operasional Dapur"
                            class="w-full bg-slate-50 border {{ $errors->has('nama_akun') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        <x-input-error :messages="$errors->get('nama_akun')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                    </div>
                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Keterangan
                            <span class="text-slate-400 font-normal capitalize">(Opsional)</span></label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                            placeholder="Contoh: Beli sabun, token listrik, dll"
                            class="w-full bg-slate-50 border {{ $errors->has('keterangan') ? 'border-rose-300' : 'border-slate-200' }} rounded-xl px-4 py-3 text-base sm:text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                    </div>

                    <div class="pt-4 pb-2">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">
                            Simpan Akun Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
