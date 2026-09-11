@extends('layouts.app')

@section('title', 'Multi-Harga per Cabang')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative">

        {{-- ========== PAGE HEADER ========== --}}
        <div
            class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">

            {{-- Efek Glow Latar Belakang --}}
            <div
                class="absolute -top-10 -right-10 w-32 h-32 sm:w-40 sm:h-40 bg-blue-50 rounded-full blur-2xl opacity-80 pointer-events-none">
            </div>

            {{-- Title & Icon --}}
            <div class="relative z-10 flex items-center gap-3.5 sm:gap-4">
                <div
                    class="w-11 h-11 sm:w-12 sm:h-12 bg-blue-50 text-blue-600 rounded-xl sm:rounded-2xl flex items-center justify-center shrink-0 border border-blue-100/50 shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Multi-Harga Cabang</h1>
                    <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5 sm:mt-1">Atur harga jual berbeda
                        untuk setiap cabang operasional.</p>
                </div>
            </div>

            {{-- Action Button --}}
            <div class="relative z-10 shrink-0 w-full sm:w-auto">
                <button onclick="openModal('create-modal')"
                    class="w-full sm:w-auto flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 sm:py-2.5 rounded-xl text-sm shadow-[0_4px_12px_rgba(37,99,235,0.2)] transition-all active:scale-95">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Harga Custom
                </button>
            </div>
        </div>

        {{-- ========== ALERT BOX (Dipanggil via JS) ========== --}}
        <div id="alertBox" class="hidden"></div>

        {{-- ========== FILTER ========== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-end">
                <div class="w-full sm:flex-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Filter
                        Cabang</label>
                    <select name="cabang_id"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all cursor-pointer">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                {{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-full sm:flex-1">
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Status
                        Aktif</label>
                    <select name="status"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit"
                        class="flex-1 sm:flex-none bg-slate-800 hover:bg-slate-900 text-white px-6 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95 shadow-sm text-center">
                        Terapkan
                    </button>

                    @if (request('cabang_id') || request('status'))
                        <a href="{{ route('harga-cabang.index') }}"
                            class="flex-1 sm:flex-none bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-bold transition-all text-center shadow-sm">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ========== DATA CONTAINER ========== --}}
        <div
            class="bg-white lg:bg-transparent rounded-xl sm:rounded-2xl lg:shadow-soft lg:border lg:border-slate-200/80 overflow-hidden flex flex-col">

            {{-- Header Tabel (Hanya tampil di Desktop) --}}
            <div class="hidden lg:flex p-4 border-b border-slate-100 items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    <h3 class="font-bold text-slate-800 text-sm">Daftar Harga Custom</h3>
                </div>
                <span
                    class="text-xs font-bold text-slate-500 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-sm">
                    {{ count($hargaCabangs) }} Data
                </span>
            </div>

            {{-- 1. TAMPILAN MOBILE (CLEAN CARDS) --}}
            <div class="block lg:hidden bg-slate-50/50 p-3 sm:p-4 rounded-xl border border-slate-200/80 space-y-3">
                @forelse($hargaCabangs as $harga)
                    <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-sm relative overflow-hidden">
                        {{-- Aksen Garis Kiri Status --}}
                        <div
                            class="absolute left-0 top-0 bottom-0 w-1 {{ $harga->is_active ? 'bg-emerald-500' : 'bg-slate-300' }}">
                        </div>

                        <div class="flex justify-between items-start mb-3 pb-3 border-b border-slate-100 pl-2">
                            <div>
                                <span
                                    class="inline-block px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/60 mb-1.5">
                                    {{ $harga->cabang->nama_cabang ?? '-' }}
                                </span>
                                <h3 class="font-bold text-slate-800 text-sm leading-tight">
                                    {{ $harga->voucher->nama_produk ?? '-' }}</h3>
                            </div>
                            <div class="shrink-0 text-right">
                                @if ($harga->is_active)
                                    <span
                                        class="px-2 py-0.5 rounded text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 uppercase tracking-wide">Aktif</span>
                                @else
                                    <span
                                        class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-50 text-slate-500 border border-slate-200/80 uppercase tracking-wide">Nonaktif</span>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-3 pl-2">
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Harga Master
                                </p>
                                <p class="font-semibold text-slate-600 text-xs">Rp
                                    {{ number_format($harga->voucher->harga_jual ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Harga Custom
                                </p>
                                <p class="font-extrabold text-blue-600 text-sm">
                                    Rp {{ number_format($harga->harga_jual, 0, ',', '.') }}
                                    @if ($harga->voucher && $harga->harga_jual > $harga->voucher->harga_jual)
                                        <span class="text-[10px] text-rose-500 ml-0.5" title="Lebih mahal">↑</span>
                                    @elseif($harga->voucher && $harga->harga_jual < $harga->voucher->harga_jual)
                                        <span class="text-[10px] text-emerald-500 ml-0.5" title="Lebih murah">↓</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-slate-100 pl-2">
                            <p class="text-[10px] text-slate-500 font-medium">
                                Periode:
                                @if ($harga->tanggal_mulai || $harga->tanggal_berakhir)
                                    {{ $harga->tanggal_mulai?->format('d/m/Y') ?? '∞' }} -
                                    {{ $harga->tanggal_berakhir?->format('d/m/Y') ?? '∞' }}
                                @else
                                    Selamanya
                                @endif
                            </p>
                            <div class="flex gap-2">
                                <button onclick="openModal('edit-modal-{{ $harga->id }}')"
                                    class="p-1.5 bg-amber-50 text-amber-600 rounded-md hover:bg-amber-100 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                    </svg>
                                </button>
                                <button onclick="hapusHarga({{ $harga->id }})"
                                    class="p-1.5 bg-rose-50 text-rose-600 rounded-md hover:bg-rose-100 transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center flex flex-col items-center">
                        <svg class="w-10 h-10 mb-2 text-slate-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <p class="font-medium text-slate-500 text-xs">Belum ada harga custom</p>
                    </div>
                @endforelse
            </div>

            {{-- 2. TAMPILAN DESKTOP (TABEL) --}}
            <div class="hidden lg:block overflow-x-auto bg-white">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead
                        class="bg-white text-[11px] font-extrabold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-4 text-center w-12">No</th>
                            <th class="px-5 py-4 text-left">Produk</th>
                            <th class="px-5 py-4 text-left">Cabang</th>
                            <th class="px-5 py-4 text-right">Harga Master</th>
                            <th class="px-5 py-4 text-right">Harga Custom</th>
                            <th class="px-5 py-4 text-center">Periode</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm font-medium">
                        @php $noDesktop = 1; @endphp
                        @forelse($hargaCabangs as $harga)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-5 py-3.5 text-center text-slate-400 font-bold">{{ $noDesktop++ }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $harga->voucher->nama_produk ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="inline-block px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200 shadow-sm">
                                        {{ $harga->cabang->nama_cabang ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right text-slate-500">
                                    Rp {{ number_format($harga->voucher->harga_jual ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-extrabold text-blue-600">
                                    Rp {{ number_format($harga->harga_jual, 0, ',', '.') }}
                                    @if ($harga->voucher && $harga->harga_jual > $harga->voucher->harga_jual)
                                        <span class="text-xs text-rose-500 ml-1" title="Lebih mahal dari master">↑</span>
                                    @elseif($harga->voucher && $harga->harga_jual < $harga->voucher->harga_jual)
                                        <span class="text-xs text-emerald-500 ml-1"
                                            title="Lebih murah dari master">↓</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center text-xs text-slate-500">
                                    @if ($harga->tanggal_mulai || $harga->tanggal_berakhir)
                                        {{ $harga->tanggal_mulai?->format('d/m/Y') ?? '∞' }} –
                                        {{ $harga->tanggal_berakhir?->format('d/m/Y') ?? '∞' }}
                                    @else
                                        <span class="italic">Selamanya</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if ($harga->is_active)
                                        <span
                                            class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/80 uppercase tracking-wide">Aktif</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-50 text-slate-500 border border-slate-200/80 uppercase tracking-wide">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="openModal('edit-modal-{{ $harga->id }}')"
                                            class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition shadow-sm"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                            </svg>
                                        </button>
                                        <button onclick="hapusHarga({{ $harga->id }})"
                                            class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition shadow-sm"
                                            title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <p class="font-medium text-slate-600">Belum ada harga custom</p>
                                        <p class="text-xs mt-1">Klik "Tambah Harga Custom" untuk memulai</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($hargaCabangs->hasPages())
                <div
                    class="px-4 md:px-5 py-3 md:py-4 border-t border-slate-200 bg-white lg:bg-slate-50/50 rounded-b-xl lg:rounded-none mt-2 lg:mt-0 shadow-sm lg:shadow-none">
                    {{ $hargaCabangs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ========== MODAL TAMBAH ========== --}}
    <form method="POST" action="{{ route('harga-cabang.store') }}" id="formCreate" onsubmit="submitCreate(event)">
        @csrf
        <div id="create-modal"
            class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto transition-opacity">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-xl my-8 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Tambah Harga Cabang
                    </h3>
                    <button type="button" onclick="closeModal('create-modal')"
                        class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition active:scale-95">
                        &times;
                    </button>
                </div>

                <div class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Cabang <span
                                class="text-rose-500">*</span></label>
                        <select name="cabang_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all cursor-pointer">
                            <option value="">-- Pilih Cabang --</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Produk <span
                                class="text-rose-500">*</span></label>
                        <select name="voucher_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all cursor-pointer">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($vouchers as $voucher)
                                <option value="{{ $voucher->id }}">
                                    {{ $voucher->nama_produk }} (Rp
                                    {{ number_format($voucher->harga_jual, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Harga Jual
                            Custom <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                            <input type="text" name="harga_jual" required inputmode="numeric"
                                oninput="formatCurrency(this)" placeholder="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Harga Beli
                            <span class="text-slate-400 font-medium normal-case tracking-normal">(Opsional)</span></label>
                        <div class="relative">
                            <span
                                class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                            <input type="text" name="harga_beli" inputmode="numeric" oninput="formatCurrency(this)"
                                placeholder="0"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Mulai
                                Berlaku</label>
                            <input type="date" name="tanggal_mulai"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Berakhir
                                Pada</label>
                            <input type="date" name="tanggal_berakhir"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Catatan <span
                                class="text-slate-400 font-medium normal-case tracking-normal">(Opsional)</span></label>
                        <input type="text" name="catatan" placeholder="Contoh: promo akhir tahun"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50/50">
                    <button type="button" onclick="closeModal('create-modal')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all active:scale-95">Batal</button>
                    <button type="submit" id="btnCreate"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl text-xs shadow-sm transition-all active:scale-95 disabled:opacity-50">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- ========== MODAL EDIT ========== --}}
    @foreach ($hargaCabangs as $harga)
        <form method="POST" action="{{ route('harga-cabang.update', $harga->id) }}"
            onsubmit="submitEdit(event, {{ $harga->id }})">
            @csrf @method('PUT')
            <div id="edit-modal-{{ $harga->id }}"
                class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto transition-opacity">
                <div class="bg-white rounded-2xl w-full max-w-md shadow-xl my-8 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit Harga Custom
                        </h3>
                        <button type="button" onclick="closeModal('edit-modal-{{ $harga->id }}')"
                            class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition active:scale-95">
                            &times;
                        </button>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="bg-slate-50/80 rounded-xl p-3.5 border border-slate-100 text-sm flex flex-col gap-2">
                            <div class="flex justify-between">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Produk</p>
                                <p class="font-bold text-slate-800 text-right">{{ $harga->voucher->nama_produk ?? '-' }}
                                </p>
                            </div>
                            <div class="flex justify-between border-t border-slate-200/60 pt-2">
                                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cabang</p>
                                <p class="font-bold text-slate-800 text-right">{{ $harga->cabang->nama_cabang ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Harga
                                Jual Baru <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span
                                    class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                                <input type="text" name="harga_jual"
                                    value="{{ number_format($harga->harga_jual, 0, ',', '.') }}" required
                                    inputmode="numeric" oninput="formatCurrency(this)"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-bold focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Harga
                                Beli <span
                                    class="text-slate-400 font-medium normal-case tracking-normal">(Opsional)</span></label>
                            <div class="relative">
                                <span
                                    class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">Rp</span>
                                <input type="text" name="harga_beli"
                                    value="{{ $harga->harga_beli ? number_format($harga->harga_beli, 0, ',', '.') : '' }}"
                                    inputmode="numeric" oninput="formatCurrency(this)" placeholder="0"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Mulai
                                    Berlaku</label>
                                <input type="date" name="tanggal_mulai"
                                    value="{{ $harga->tanggal_mulai?->format('Y-m-d') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all cursor-pointer">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Berakhir
                                    Pada</label>
                                <input type="date" name="tanggal_berakhir"
                                    value="{{ $harga->tanggal_berakhir?->format('Y-m-d') }}"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all cursor-pointer">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">Catatan
                                <span
                                    class="text-slate-400 font-medium normal-case tracking-normal">(Opsional)</span></label>
                            <input type="text" name="catatan" value="{{ $harga->catatan }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>

                        <label
                            class="flex items-center gap-2.5 cursor-pointer bg-slate-50/50 border border-slate-100 p-3 rounded-xl mt-2">
                            <input type="checkbox" name="is_active" value="1"
                                {{ $harga->is_active ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500/30">
                            <span class="text-sm font-bold text-slate-700">Tandai sebagai Aktif</span>
                        </label>
                    </div>

                    <div class="px-5 py-4 border-t border-slate-100 flex justify-end gap-2 bg-slate-50/50">
                        <button type="button" onclick="closeModal('edit-modal-{{ $harga->id }}')"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition-all active:scale-95">Batal</button>
                        <button type="submit" id="btnEdit-{{ $harga->id }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-xl text-xs shadow-sm transition-all active:scale-95 disabled:opacity-50">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    @endforeach

    {{-- ========== SMART SCROLL ARROW (MOBILE ONLY) ========== --}}
    @if (count($hargaCabangs) > 0)
        <button id="smartScrollBtn"
            class="lg:hidden fixed bottom-24 right-5 z-[40] bg-blue-600/95 backdrop-blur-sm hover:bg-blue-700 text-white w-10 h-10 rounded-full shadow-[0_4px_12px_rgba(37,99,235,0.4)] flex items-center justify-center transition-transform active:scale-90">
            <svg id="scrollIcon" class="w-5 h-5 transition-transform duration-300 ease-in-out" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3">
                </path>
            </svg>
        </button>
    @endif

    {{-- ========== SCRIPT ========== --}}
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
        // ========== SMART SCROLL ==========
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

        // ========== FORMAT CURRENCY ==========
        function formatCurrency(input) {
            let value = input.value.replace(/[^\d]/g, '');
            input.value = value ? parseInt(value).toLocaleString('id-ID') : '';
        }

        // ========== BERSIHKAN FORMAT CURRENCY SEBELUM KIRIM ==========
        function cleanCurrencyFormData(form) {
            const formData = new FormData(form);
            ['harga_jual', 'harga_beli'].forEach(field => {
                if (formData.has(field)) {
                    const clean = String(formData.get(field)).replace(/\./g, '');
                    formData.set(field, clean);
                }
            });
            return formData;
        }

        // ========== MODAL ==========
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // ========== ALERT BERSAJA GAYA "FLASH MESSAGE" ==========
        function showAlert(type, message) {
            const box = document.getElementById('alertBox');

            if (type === 'success') {
                box.className =
                    'flex items-start gap-2.5 sm:gap-3 bg-emerald-50 border border-emerald-200/80 text-emerald-800 px-3 sm:px-4 py-3 sm:py-3.5 rounded-xl text-[11px] sm:text-sm font-medium animate-[fadeIn_0.3s_ease-out] mb-4 shadow-sm';
                box.innerHTML =
                    `<svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>${message}</span>`;
            } else {
                box.className =
                    'flex items-start gap-2.5 sm:gap-3 bg-rose-50 border border-rose-200/80 text-rose-800 px-3 sm:px-4 py-3 sm:py-3.5 rounded-xl text-[11px] sm:text-sm font-medium animate-[fadeIn_0.3s_ease-out] mb-4 shadow-sm';
                box.innerHTML =
                    `<svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg><span>${message}</span>`;
            }

            box.classList.remove('hidden');
            setTimeout(() => box.classList.add('hidden'), 4000);
        }

        // ========== SUBMIT CREATE ==========
        async function submitCreate(event) {
            event.preventDefault();
            const form = event.target;
            const btn = document.getElementById('btnCreate');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const formData = cleanCurrencyFormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await res.json();

                if (data.success) {
                    closeModal('create-modal');
                    showAlert('success', data.message);
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert('error', data.message || 'Gagal menyimpan.');
                    btn.disabled = false;
                    btn.textContent = 'Simpan';
                }
            } catch (err) {
                showAlert('error', 'Terjadi kesalahan koneksi.');
                btn.disabled = false;
                btn.textContent = 'Simpan';
            }
        }

        // ========== SUBMIT EDIT ==========
        async function submitEdit(event, id) {
            event.preventDefault();
            const form = event.target;
            const btn = document.getElementById(`btnEdit-${id}`);
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const formData = cleanCurrencyFormData(form);

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await res.json();

                if (data.success) {
                    closeModal(`edit-modal-${id}`);
                    showAlert('success', data.message);
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert('error', data.message || 'Gagal update.');
                    btn.disabled = false;
                    btn.textContent = 'Simpan Perubahan';
                }
            } catch (err) {
                showAlert('error', 'Terjadi kesalahan koneksi.');
                btn.disabled = false;
                btn.textContent = 'Simpan Perubahan';
            }
        }

        // ========== HAPUS ==========
        async function hapusHarga(id) {
            if (!confirm('Yakin ingin menghapus harga custom ini? Data akan dikembalikan ke harga master.')) return;

            try {
                const res = await fetch(`{{ url('/harga-cabang') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();

                if (data.success) {
                    showAlert('success', data.message);
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showAlert('error', data.message);
                }
            } catch (err) {
                showAlert('error', 'Terjadi kesalahan koneksi saat menghapus.');
            }
        }

        // ========== CLOSE MODAL CLICKS ==========
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('backdrop-blur-sm') && e.target.classList.contains('fixed')) {
                closeModal(e.target.id);
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(m => closeModal(m.id));
            }
        });
    </script>
@endsection
