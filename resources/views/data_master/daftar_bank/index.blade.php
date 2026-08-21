@extends('layouts.app')

@section('title', 'Daftar Bank')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-4 sm:space-y-6 pb-12 mt-3 sm:mt-5">

        {{-- Notifikasi Limit & Kuota Bank (Compact di Mobile) --}}
        @php
            $tenant = Auth::user()->tenant;
            $maxBank = $tenant->plan && $tenant->plan->harga == 0 ? 3 : 999;
            $currentBank = $banks->where('tenant_id', $tenant->id_tenant)->count();
            $canAddBank = $currentBank < $maxBank;
        @endphp

        @if (!$canAddBank)
            <div
                class="bg-amber-50 border border-amber-200 text-amber-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm relative overflow-hidden">
                <div class="flex items-start sm:items-center gap-2.5 sm:gap-3 relative z-10">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mt-0.5 sm:mt-0 shrink-0 text-amber-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    <p class="text-[11px] sm:text-sm font-medium leading-relaxed">
                        Batas Paket Gratis (3 bank). <strong class="hidden sm:inline">Upgrade ke PRO untuk menambah tanpa
                            batas.</strong>
                        <span class="sm:hidden font-bold">Upgrade ke PRO!</span>
                    </p>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white text-[11px] sm:text-sm font-bold px-3 py-1.5 sm:px-4 sm:py-2 rounded-lg transition-colors relative z-10 active:scale-95">
                    Upgrade Sekarang
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0 && $canAddBank)
            <div
                class="bg-blue-50/80 border border-blue-100 text-blue-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl flex items-center gap-2.5 sm:gap-3 shadow-sm">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 shrink-0 text-blue-600" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-[11px] sm:text-sm font-medium">Kuota Paket Gratis: <strong
                        class="text-blue-900">{{ $currentBank }}/{{ $maxBank }}</strong> bank.</p>
            </div>
        @endif

        {{-- Session Success Alert --}}
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

        {{-- Header & Tombol Tambah (Di Mobile: Tombol berada di sebelah kanan judul) --}}
        <div
            class="flex items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            {{-- Aksen biru hiasan --}}
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 w-full flex items-center justify-between">
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        <span
                            class="p-1.5 sm:p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0 text-sm sm:text-base">🏦</span>
                        Data Bank
                    </h1>
                    <p class="text-[10px] sm:text-sm text-slate-500 mt-1 font-medium hidden sm:block">
                        Kelola daftar rekening bank atau akun e-wallet untuk pencatatan transaksi.
                    </p>
                </div>

                <div class="shrink-0">
                    @if ($canAddBank)
                        <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal" type="button"
                            class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95">
                            <span>+</span> <span class="hidden sm:inline">Tambah Bank</span>
                        </button>
                    @else
                        <button disabled type="button" title="Batas maksimal bank tercapai"
                            class="inline-flex items-center justify-center gap-1.5 bg-slate-100 text-slate-400 cursor-not-allowed font-semibold px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm border border-slate-200">
                            <span>🔒</span> <span class="hidden sm:inline">Limit Tercapai</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Table Data Bank --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm sm:text-base font-bold text-slate-800">Rekening Terdaftar</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead
                        class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center w-12">No</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5">Nama Bank</th>
                            {{-- Kolom tanggal disembunyikan di mobile agar tabel muat --}}
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 hidden sm:table-cell">Tgl Dibuat</th>
                            <th class="px-4 sm:px-6 py-2.5 sm:py-3.5 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @forelse ($banks as $i => $item)
                            <tr class="hover:bg-slate-50/70">
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-center font-semibold text-slate-500 text-[10px] sm:text-xs">
                                    {{ $i + 1 }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 font-bold text-slate-800">
                                    {{ $item->nama_bank }}
                                </td>
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-slate-500 font-medium text-[10px] sm:text-xs hidden sm:table-cell">
                                    {{ $item->created_at_format }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center justify-center gap-1.5 sm:gap-2">
                                        @if (is_null($item->tenant_id))
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] sm:text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500">
                                                Sistem
                                            </span>
                                        @elseif(Auth::user()->role === 'super_admin')
                                            <span class="text-[10px] text-slate-400 italic">View</span>
                                        @else
                                            <button data-modal-target="edit-modal-{{ $item->id }}"
                                                data-modal-toggle="edit-modal-{{ $item->id }}" title="Edit"
                                                class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg sm:rounded-xl transition active:scale-95">
                                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                                </svg>
                                            </button>

                                            <form action="{{ route('data_master.daftar_bank.destroy', $item->id) }}"
                                                method="POST" onsubmit="return confirm('Hapus bank ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" title="Hapus"
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

                            {{-- Modal Edit per Bank --}}
                            <div id="edit-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
                                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
                                <div
                                    class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-200 p-6 sm:p-8">
                                    <div
                                        class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                                        <h3 class="text-sm sm:text-base font-bold text-slate-800">Edit Bank</h3>
                                        <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                            class="text-slate-400 hover:text-rose-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <form action="{{ route('data_master.daftar_bank.update', $item->id) }}"
                                        method="POST" class="p-5 space-y-4">
                                        @csrf @method('PUT')
                                        <div>
                                            <label
                                                class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                                                Bank</label>
                                            <input type="text" name="nama_bank" required
                                                value="{{ $item->nama_bank }}"
                                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition">
                                        </div>
                                        <div class="flex justify-end gap-2 pt-2">
                                            <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                                class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition active:scale-95">Batal</button>
                                            <button type="submit"
                                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-sm transition active:scale-95">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-10 text-slate-400 text-xs sm:text-sm">Belum ada
                                    data bank terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah Bank --}}
        <div id="tambah-modal" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm overflow-hidden animate-in fade-in zoom-in-95 duration-200">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800">Tambah Bank Baru</h3>
                    <button type="button" data-modal-toggle="tambah-modal"
                        class="text-slate-400 hover:text-rose-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('data_master.daftar_bank.store') }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                            Bank</label>
                        <input type="text" name="nama_bank" required placeholder="Contoh: BCA, BRI"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-sm text-slate-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" data-modal-toggle="tambah-modal"
                            class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition active:scale-95">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-sm transition active:scale-95">Tambah</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
