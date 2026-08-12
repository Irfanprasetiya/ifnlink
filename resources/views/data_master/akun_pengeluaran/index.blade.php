@extends('layouts.app')

@section('title', 'Daftar Akun Pengeluaran')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12">

        {{-- Header & Tombol Tambah --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    Daftar Akun Pengeluaran
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                    Kelola pos akun pengeluaran kas untuk pencatatan keuangan operasional bisnis Anda.
                </p>
            </div>
            <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal"
                class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all"
                type="button">
                <span>+</span> Tambah Akun
            </button>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Data --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Daftar Akun Aktif</h2>
                <p class="text-xs text-slate-500 mt-0.5">Rincian pos akun pengeluaran yang terdaftar dalam sistem.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/70 border-b border-slate-100 text-[13px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Nama Akun</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center w-44">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php $no = 1; @endphp
                        @forelse ($akunPengeluarans as $item)
                            @php
                                $isMaster = is_null($item->tenant_id);
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors {{ $isMaster ? 'bg-amber-50/30' : '' }}">
                                <td class="px-6 py-4 text-center font-semibold text-slate-600 text-sm">
                                    {{ $no++ }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-semibold {{ $isMaster ? 'text-gray-500' : 'text-slate-900' }}">
                                        {{ $item->nama_akun }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 text-sm">{{ $item->keterangan ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center">
                                        @if ($isMaster)
                                            {{-- Readonly untuk data master --}}
                                            <span
                                                class="inline-flex items-center gap-1.5 text-sm text-gray-400 italic whitespace-nowrap">
                                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2" />
                                                </svg>
                                                Read Only
                                            </span>
                                        @else
                                            <div class="flex items-center gap-2 whitespace-nowrap">
                                                {{-- Tombol Edit --}}
                                                <button data-modal-target="edit-modal-{{ $item->id }}"
                                                    data-modal-toggle="edit-modal-{{ $item->id }}"
                                                    class="inline-flex items-center gap-1 px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg transition shadow-sm text-sm font-medium"
                                                    title="Edit Akun">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                                    </svg>
                                                    Edit
                                                </button>

                                                {{-- Tombol Hapus --}}
                                                <form
                                                    action="{{ route('data_master.akun_pengeluaran.destroy', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus akun ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1 px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg transition shadow-sm text-sm font-medium"
                                                        title="Hapus Akun">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            @if (!$isMaster)
                                <div id="edit-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
                                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
                                    <div
                                        class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md">
                                        <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                                            <h3 class="text-base font-bold text-slate-900">Edit Akun Pengeluaran</h3>
                                            <button type="button" data-modal-hide="edit-modal-{{ $item->id }}"
                                                class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition">&times;</button>
                                        </div>

                                        <form action="{{ route('data_master.akun_pengeluaran.update', $item->id) }}"
                                            method="POST" class="space-y-4">
                                            @csrf @method('PUT')
                                            <div>
                                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama
                                                    Akun</label>
                                                <input type="text" name="nama_akun" value="{{ $item->nama_akun }}"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                                    required>
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                                                <input type="text" name="keterangan" value="{{ $item->keterangan }}"
                                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                                    placeholder="Opsional">
                                            </div>
                                            <div class="flex justify-end gap-2.5 pt-3">
                                                <button type="button" data-modal-hide="edit-modal-{{ $item->id }}"
                                                    class="px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                                <button type="submit"
                                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-base font-medium">
                                    Belum ada data akun pengeluaran.
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
            class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                <h3 class="text-base font-bold text-slate-900">Tambah Akun Pengeluaran</h3>
                <button type="button" data-modal-hide="tambah-modal"
                    class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition">&times;</button>
            </div>

            <form action="{{ route('data_master.akun_pengeluaran.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Akun</label>
                    <input type="text" name="nama_akun" value="{{ old('nama_akun') }}"
                        placeholder="Masukkan nama akun"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                        required>
                    <x-input-error :messages="$errors->get('nama_akun')" class="mt-1 text-sm" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                        placeholder="Opsional">
                    <x-input-error :messages="$errors->get('keterangan')" class="mt-1 text-sm" />
                </div>

                <div class="flex justify-end gap-2.5 pt-3">
                    <button type="button" data-modal-hide="tambah-modal"
                        class="px-5 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
