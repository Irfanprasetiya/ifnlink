@extends('layouts.app')

@section('title', 'Daftar Cabang')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12 mt-5">

        {{-- Logika Limit Cabang (TIDAK DIUBAH) --}}
        @php
            $tenant = Auth::user()->tenant;
            $maxCabang = $tenant->plan && $tenant->plan->harga == 0 ? 1 : 999;
            // Hitung cabang SELAIN Gudang
            $currentCabang = $tenant->cabang()->where('nama_cabang', '!=', 'Gudang')->count();
            $canAddCabang = $currentCabang < $maxCabang;
        @endphp

        {{-- Notifikasi Limit Cabang (Dipercantik) --}}
        @if (!$canAddCabang)
            <div
                class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 mb-2 bg-amber-50 border border-amber-200 rounded-xl shadow-sm gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-100 rounded-lg text-amber-600 shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <span class="text-sm text-amber-800 font-medium">
                        Paket Gratis hanya mengizinkan 1 cabang tambahan. <strong class="font-bold">Upgrade ke PRO</strong>
                        untuk cabang tanpa batas.
                    </span>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-bold text-amber-700 bg-amber-100 hover:bg-amber-200 rounded-lg transition-colors whitespace-nowrap">
                    Upgrade Sekarang <span class="ml-1">→</span>
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0 && $canAddCabang)
            <div class="flex items-center p-4 mb-2 bg-blue-50 border border-blue-200 rounded-xl shadow-sm gap-3">
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-sm text-blue-800 font-medium">
                    Info Paket Gratis: Maksimal 1 cabang tambahan (Cabang <span class="font-bold">Gudang</span> bawaan
                    sistem tidak dihitung).
                </span>
            </div>
        @endif

        {{-- Header & Tombol Tambah --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
            {{-- Ornamen Background Lembut --}}
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </span>
                    Daftar Cabang
                </h1>
                <p class="text-sm text-slate-500 mt-2 font-medium">
                    Kelola data lokasi cabang dan gudang untuk operasional bisnis Anda.
                </p>
            </div>

            <div class="relative z-10">
                @if ($canAddCabang)
                    <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm shadow-sm transition-all w-full sm:w-auto"
                        type="button">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Cabang
                    </button>
                @else
                    <button disabled
                        class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-400 font-medium px-5 py-2.5 rounded-lg text-sm border border-slate-200 cursor-not-allowed w-full sm:w-auto"
                        type="button" title="Kuota cabang penuh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
                        </svg>
                        Tambah Cabang
                    </button>
                @endif
            </div>
        </div>

        {{-- Notifikasi Sukses / Error --}}
        @if (session('success'))
            <div id="alert-success"
                class="flex items-center justify-between p-4 mb-4 text-emerald-800 bg-emerald-50 border border-emerald-200 rounded-xl shadow-sm"
                role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
                <button type="button" class="text-emerald-500 hover:bg-emerald-100 rounded-lg p-1.5 transition-colors"
                    data-dismiss-target="#alert-success" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div id="alert-error"
                class="flex items-center justify-between p-4 mb-4 text-rose-800 bg-rose-50 border border-rose-200 rounded-xl shadow-sm"
                role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
                <button type="button" class="text-rose-500 hover:bg-rose-100 rounded-lg p-1.5 transition-colors"
                    data-dismiss-target="#alert-error" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Table Container --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mt-6">
            <div class="p-5 sm:p-6 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Data Cabang</h2>
                    <p class="text-sm text-slate-500 mt-1">Seluruh data cabang perusahaan yang terdaftar di sistem.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Nama Cabang</th>
                            <th class="px-6 py-4">Alamat</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php $no = 1; @endphp
                        @forelse ($cabangs as $cabang)
                            @php
                                $isGudang = strtolower($cabang->nama_cabang) === 'gudang';
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors group {{ $isGudang ? 'bg-amber-50/30' : '' }}">
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-xs font-semibold text-slate-600 border border-slate-200">
                                        {{ $no++ }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-medium {{ $isGudang ? 'text-slate-600' : 'text-slate-800' }}">
                                        {{ $cabang->nama_cabang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-sm">
                                    {{ $cabang->alamat_cabang ?: '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm">
                                    {{ $cabang->keterangan ?: '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @if ($isGudang)
                                            {{-- Gudang: System Read Only --}}
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-400 border border-slate-200/60">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
                                                </svg>
                                                System
                                            </span>
                                        @elseif(Auth::user()->role === 'super_admin')
                                            {{-- Super Admin: Read Only --}}
                                            <span class="text-xs text-slate-400 italic">Read Only</span>
                                        @else
                                            {{-- Tombol Edit --}}
                                            <button type="button" data-modal-target="edit-modal-{{ $cabang->id }}"
                                                data-modal-toggle="edit-modal-{{ $cabang->id }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-100 transition-all shadow-sm"
                                                title="Edit Cabang">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('cabang.destroy', $cabang->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all shadow-sm"
                                                    title="Hapus Cabang">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Edit (Untuk non-Gudang) --}}
                            @if (!$isGudang)
                                <div id="edit-modal-{{ $cabang->id }}" tabindex="-1" aria-hidden="true"
                                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
                                    <div
                                        class="bg-white rounded-xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md">
                                        <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                                            <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Edit Data Cabang
                                            </h3>
                                            <button type="button" data-modal-toggle="edit-modal-{{ $cabang->id }}"
                                                class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-1 rounded-md transition">&times;</button>
                                        </div>

                                        <form method="POST" action="{{ route('cabang.update', $cabang->id) }}"
                                            class="space-y-5">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama
                                                    Cabang</label>
                                                <input type="text" name="nama_cabang"
                                                    value="{{ $cabang->nama_cabang }}" required
                                                    class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Alamat
                                                    Cabang</label>
                                                <input type="text" name="alamat_cabang"
                                                    value="{{ $cabang->alamat_cabang }}" required
                                                    class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan
                                                    <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                                <input type="text" name="keterangan"
                                                    value="{{ $cabang->keterangan }}"
                                                    class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition">
                                            </div>
                                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                                <button type="button" data-modal-toggle="edit-modal-{{ $cabang->id }}"
                                                    class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</button>
                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm shadow-sm transition">Simpan
                                                    Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada data cabang yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <form method="POST" action="{{ route('data_master.cabang.store') }}">
        @csrf
        <div id="crud-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div
                class="bg-white rounded-xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Cabang Baru
                    </h3>
                    <button type="button" data-modal-toggle="crud-modal"
                        class="text-slate-400 hover:text-rose-500 hover:bg-rose-50 p-1 rounded-md transition">&times;</button>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="nama_cabang" class="block text-sm font-medium text-slate-700 mb-1.5">Nama
                            Cabang</label>
                        <input type="text" name="nama_cabang" id="nama_cabang" value="{{ old('nama_cabang') }}"
                            required placeholder="Contoh: Cabang Jakarta"
                            class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition">
                        <x-input-error :messages="$errors->get('nama_cabang')" class="mt-1.5 text-xs text-rose-500" />
                    </div>

                    <div>
                        <label for="alamat_cabang" class="block text-sm font-medium text-slate-700 mb-1.5">Alamat
                            Lengkap</label>
                        <input type="text" name="alamat_cabang" id="alamat_cabang"
                            value="{{ old('alamat_cabang') }}" required placeholder="Contoh: Jl. Sudirman No. 123"
                            class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition">
                        <x-input-error :messages="$errors->get('alamat_cabang')" class="mt-1.5 text-xs text-rose-500" />
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan <span
                                class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}"
                            placeholder="Tulis keterangan operasional"
                            class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition">
                        <x-input-error :messages="$errors->get('keterangan')" class="mt-1.5 text-xs text-rose-500" />
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-6">
                        <button type="button" data-modal-toggle="crud-modal"
                            class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm shadow-sm transition">
                            Simpan Cabang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
