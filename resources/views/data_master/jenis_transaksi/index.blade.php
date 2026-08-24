@extends('layouts.app')

@section('title', 'Data Jenis Transaksi')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12">

        {{-- Header & Tombol Tambah --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </span>
                    Data Jenis Transaksi
                </h1>
                <p class="text-sm text-slate-500 mt-2 font-medium">
                    Kelola klasifikasi jenis transaksi untuk pencatatan operasional keuangan Anda.
                </p>
            </div>
            {{-- <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal"
                class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm shadow-sm transition-all"
                type="button">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Jenis
            </button> --}}
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-lg text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-lg text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Table Container --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Daftar Jenis Transaksi</h2>
                    <p class="text-sm text-slate-500 mt-1">Rincian data jenis transaksi yang terdaftar dalam sistem.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Jenis Transaksi</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($jenisTransaksi as $i => $item)
                            @php
                                $isMaster = is_null($item->tenant_id);
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-6 py-4 text-center font-medium text-slate-500 text-sm">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-sm font-medium {{ $isMaster ? 'text-slate-500' : 'text-slate-800' }}">
                                            {{ $item->nama_transaksi }}
                                        </span>
                                        @if ($isMaster)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                                Sistem
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm">
                                    {{ $item->keterangan ?: '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-1">
                                        @if ($isMaster)
                                            <span class="text-xs text-slate-400 italic">Read-Only</span>
                                        @else
                                            {{-- Tombol Edit Icon --}}
                                            <button data-modal-target="edit-modal-{{ $item->id }}"
                                                data-modal-toggle="edit-modal-{{ $item->id }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors"
                                                title="Edit Data">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus Icon --}}
                                            <form action="{{ route('data_master.jenis-transaksi.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus jenis transaksi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-md text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors"
                                                    title="Hapus Data">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
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

                            {{-- Modal Edit --}}
                            @if (!$isMaster)
                                <div id="edit-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
                                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
                                    <div
                                        class="bg-white rounded-xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md">
                                        <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                                            <h3 class="text-lg font-semibold text-slate-800">Edit Jenis Transaksi</h3>
                                            <button type="button" data-modal-hide="edit-modal-{{ $item->id }}"
                                                class="text-slate-400 hover:text-slate-600 transition">&times;</button>
                                        </div>

                                        <form action="{{ route('data_master.jenis-transaksi.update', $item->id) }}"
                                            method="POST" class="space-y-5">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama
                                                    Transaksi</label>
                                                <input type="text" name="nama_transaksi"
                                                    value="{{ $item->nama_transaksi }}"
                                                    class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition"
                                                    required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan
                                                    <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                                <textarea name="keterangan" rows="3"
                                                    class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition"
                                                    placeholder="Tulis keterangan singkat">{{ $item->keterangan }}</textarea>
                                            </div>
                                            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                                                <button type="button" data-modal-hide="edit-modal-{{ $item->id }}"
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
                                <td colspan="4" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada data jenis transaksi.</p>
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
    <div id="tambah-modal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div
            class="bg-white rounded-xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-lg font-semibold text-slate-800">Tambah Jenis Transaksi</h3>
                <button type="button" data-modal-hide="tambah-modal"
                    class="text-slate-400 hover:text-slate-600 transition">&times;</button>
            </div>

            <form action="{{ route('data_master.jenis-transaksi.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Transaksi</label>
                    <input type="text" name="nama_transaksi" required placeholder="Contoh: Pembayaran Listrik"
                        class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Keterangan <span
                            class="text-slate-400 font-normal">(Opsional)</span></label>
                    <textarea name="keterangan" rows="3"
                        class="w-full bg-white border border-slate-300 rounded-lg px-4 py-2.5 text-sm text-slate-900 focus:outline-none focus:ring-1 focus:ring-blue-600 focus:border-blue-600 transition"
                        placeholder="Tulis keterangan singkat"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" data-modal-hide="tambah-modal"
                        class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-700 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm shadow-sm transition">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
