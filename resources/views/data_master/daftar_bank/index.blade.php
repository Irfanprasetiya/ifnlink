@extends('layouts.app')

@section('title', 'Daftar Bank')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12">

        {{-- Notifikasi Limit & Kuota Bank --}}
        @php
            $tenant = Auth::user()->tenant;
            $maxBank = $tenant->plan && $tenant->plan->harga == 0 ? 3 : 999;
            $currentBank = $banks->where('tenant_id', $tenant->id_tenant)->count();
            $canAddBank = $currentBank < $maxBank;
        @endphp

        @if (!$canAddBank)
            <div
                class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-2xl text-xs sm:text-sm font-medium flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <span>Paket Gratis maksimal 3 bank. <strong>Upgrade ke PRO</strong> untuk menambah bank tanpa
                        batas.</span>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="text-xs sm:text-sm font-bold text-amber-800 hover:text-amber-900 underline whitespace-nowrap ml-3">
                    Upgrade →
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0 && $canAddBank)
            <div
                class="bg-blue-50 border border-blue-100 text-blue-800 px-5 py-4 rounded-2xl text-xs sm:text-sm font-medium flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Paket Gratis: <strong>{{ $currentBank }}/{{ $maxBank }}</strong> bank terdaftar.</span>
            </div>
        @endif

        {{-- Session Success Alert --}}
        @if (session('success'))
            <div id="alert-success"
                class="flex items-center justify-between bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-2xl text-xs sm:text-sm font-medium shadow-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="text-emerald-400 hover:text-emerald-700 font-bold text-lg"
                    data-dismiss-target="#alert-success">&times;</button>
            </div>
        @endif

        {{-- Header & Tombol Tambah --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    <span>🏦</span> Daftar Data Bank & Dompet
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                    Kelola daftar rekening bank atau akun e-wallet untuk keperluan transaksi dan pencatatan kas toko.
                </p>
            </div>
            <div>
                @if ($canAddBank)
                    <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal"
                        class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm shadow-md shadow-blue-500/20 transition-all"
                        type="button">
                        <span>+</span> Tambah Bank
                    </button>
                @else
                    <button disabled
                        class="inline-flex items-center justify-center gap-1.5 bg-slate-200 text-slate-400 cursor-not-allowed font-semibold px-5 py-2.5 rounded-xl text-xs sm:text-sm"
                        type="button" title="Batas maksimal bank tercapai">
                        <span>🔒</span> Tambah Bank (Penuh)
                    </button>
                @endif
            </div>
        </div>

        {{-- Table Data Bank --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Rekening Terdaftar</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar bank/e-wallet yang aktif di sistem.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th scope="col" class="px-6 py-3.5 text-center">No</th>
                            <th scope="col" class="px-6 py-3.5">Nama Bank / E-Wallet</th>
                            <th scope="col" class="px-6 py-3.5">Tanggal Dibuat</th>
                            <th scope="col" class="px-6 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($banks as $i => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-center font-semibold text-slate-600 text-xs">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-900 whitespace-nowrap">
                                    {{ $item->nama_bank }}
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium text-xs">
                                    {{ $item->created_at_format }}
                                </td>
                                <td class="px-6 py-4 flex items-center justify-center gap-2">
                                    @if (is_null($item->tenant_id))
                                        {{-- Data Master: System Read Only --}}
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-500 italic">
                                            Sistem (Read-only)
                                        </span>
                                    @elseif(Auth::user()->role === 'super_admin')
                                        {{-- Super Admin: Read Only --}}
                                        <span class="text-xs text-slate-400 italic">Read Only</span>
                                    @else
                                        {{-- Tombol Edit --}}
                                        <button data-modal-target="edit-modal-{{ $item->id }}"
                                            data-modal-toggle="edit-modal-{{ $item->id }}"
                                            class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-xl transition shadow-sm"
                                            title="Edit Bank">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                            </svg>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('data_master.daftar_bank.destroy', $item->id) }}"
                                            method="POST" onsubmit="return confirm('Yakin ingin menghapus bank ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl transition shadow-sm"
                                                title="Hapus Bank">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal Edit per Bank --}}
                            <div id="edit-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
                                <div
                                    class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
                                    <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                                        <h3 class="text-base font-bold text-slate-900">Edit Data Bank</h3>
                                        <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                            class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition">&times;</button>
                                    </div>
                                    <form action="{{ route('data_master.daftar_bank.update', $item->id) }}"
                                        method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label
                                                class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                                Bank / E-Wallet</label>
                                            <input type="text" name="nama_bank" required
                                                value="{{ $item->nama_bank }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                        </div>
                                        <div class="flex justify-end gap-2.5 pt-3">
                                            <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all">
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-sm font-medium">
                                    Belum ada data bank terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah Bank --}}
        <div id="tambah-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div
                class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Tambah Bank / E-Wallet Baru</h3>
                    <button type="button" data-modal-toggle="tambah-modal"
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition">&times;</button>
                </div>
                <form action="{{ route('data_master.daftar_bank.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama Bank /
                            E-Wallet</label>
                        <input type="text" name="nama_bank" required placeholder="Contoh: BCA, BRI, Dana, dll"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                    <div class="flex justify-end gap-2.5 pt-3">
                        <button type="button" data-modal-toggle="tambah-modal"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all">
                            Simpan Bank
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
