@extends('layouts.app')

@section('title', 'Laporan Bank')

@section('container')
    {{-- Tambahan px-2 sm:px-4 agar tidak mepet layar hp --}}
    <div class="w-full max-w-7xl mx-auto space-y-4 pb-12 mt-3 px-2 sm:px-4">

        {{-- Header & Tombol Export --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-3 w-full">
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="p-1.5 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        Riwayat Transaksi
                    </h1>
                    <p class="text-xs text-slate-500 mt-1 font-medium hidden sm:block">
                        Pantau dan kelola riwayat transaksi keuangan pada bank dan kas cabang.
                    </p>
                </div>

                @if (request('cabang_id') && request('user_id'))
                    <div class="shrink-0 w-full sm:w-auto">
                        <a href="{{ route('laporan_bank.rekap', request()->only(['tanggal', 'cabang_id', 'user_id'])) }}"
                            class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold px-4 py-2.5 rounded-xl text-sm shadow-sm transition-all w-full sm:w-auto active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Rekap Laporan
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Filter Box --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-2">
            <button type="button" onclick="toggleFilter()"
                class="w-full flex md:hidden items-center justify-between p-4 bg-slate-50/80 text-slate-700 active:bg-slate-100 transition-colors">
                <span class="font-bold text-xs uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Pencarian & Filter Data
                </span>
                <svg id="filterIcon" class="w-4 h-4 text-slate-400 transform transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div id="filterBody" class="hidden md:block p-4 sm:p-5 border-t border-slate-100 md:border-t-0">
                <form method="GET" action="{{ route('laporan-bank.admin.index') }}"
                    class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 items-end">
                    <div>
                        <label for="tanggal"
                            class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            value="{{ request('tanggal', now()->toDateString()) }}"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition cursor-pointer">
                    </div>

                    <div>
                        <label for="cabang_id"
                            class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Cabang</label>
                        <select name="cabang_id" id="cabang_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition appearance-none cursor-pointer">
                            <option value="">-- Pilih Cabang --</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}"
                                    {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="user_id"
                            class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5 ml-1">Akun
                            Operator</label>
                        <select name="user_id" id="user_id"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition appearance-none cursor-pointer">
                            <option value="">-- Pilih Akun --</option>
                        </select>
                    </div>

                    <div class="sm:col-span-3 flex justify-end pt-2">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all w-full sm:w-auto active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Alerts --}}
        @if ($errors->any())
            <div
                class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-sm flex gap-3 shadow-sm mb-2">
                <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('warning'))
            <div
                class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl text-sm flex items-center gap-3 shadow-sm mb-2">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                {{ session('warning') }}
            </div>
        @endif

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm flex items-center gap-3 shadow-sm mb-2">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl text-sm flex items-center gap-3 shadow-sm mb-2">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Data List (Mobile: Cards, Desktop: Table) --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="px-4 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Daftar Riwayat Transaksi
                    </h2>
                </div>
                <span
                    class="text-xs font-bold text-slate-500 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-sm">
                    {{ count($transaksis) }} Data
                </span>
            </div>

            @if (count($transaksis) > 0)

                {{-- VIEW MOBILE: List Card --}}
                <div class="block lg:hidden bg-slate-50/30 p-3 sm:p-4 space-y-3.5">
                    @foreach ($transaksis as $index => $trx)
                        <div
                            class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm active:scale-[0.99] transition-transform relative overflow-hidden">

                            {{-- Aksen Garis Kiri untuk Card --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500/80"></div>

                            <div class="flex justify-between items-start mb-4">
                                <div class="flex gap-3">
                                    {{-- Box Nomor Urut --}}
                                    <div
                                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 font-extrabold text-xs flex items-center justify-center shrink-0 border border-blue-100/50 shadow-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 mb-1 border border-slate-200/60">
                                            {{ $trx->bank->nama_bank ?? '-' }}
                                        </span>
                                        <h3 class="font-bold text-slate-800 text-sm">
                                            {{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}
                                        </h3>
                                        <p class="text-[11px] font-medium text-slate-500 mt-0.5 flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Laba
                                    </p>
                                    <p
                                        class="font-extrabold text-sm {{ ($trx->laba_bersih ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ ($trx->laba_bersih ?? 0) >= 0 ? '+' : '-' }}Rp
                                        {{ number_format(abs($trx->laba_bersih ?? 0), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="bg-slate-50/80 border border-slate-100 rounded-xl p-3.5 grid grid-cols-2 gap-y-3 gap-x-4 text-xs mb-3">
                                <div>
                                    <p class="text-slate-500 font-medium mb-1">Nominal Trx</p>
                                    <p class="font-bold text-slate-700">Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-slate-500 font-medium mb-1">Total Bayar</p>
                                    <p class="font-bold text-slate-700">Rp
                                        {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div
                                    class="col-span-2 pt-2 border-t border-slate-200/60 flex justify-between items-center">
                                    <p class="text-slate-500 font-medium">Saldo Akhir Bank</p>
                                    <p class="font-bold text-blue-700 text-sm">Rp
                                        {{ number_format($trx->saldo_akhir_dynamic ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="col-span-2 flex justify-between items-center">
                                    <p class="text-slate-500 font-medium">Saldo Kas</p>
                                    <p
                                        class="font-bold text-sm {{ ($trx->saldo_kas ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                        Rp {{ number_format(abs($trx->saldo_kas ?? 0), 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            @if ($trx->keterangan)
                                <p
                                    class="text-xs text-slate-600 bg-slate-100/70 border border-slate-100 px-3.5 py-2.5 rounded-lg italic mb-3">
                                    "{{ $trx->keterangan }}"</p>
                            @endif

                            <div class="flex gap-2 justify-end pt-1">
                                @if (Auth::user()->role === 'super_admin')
                                    <span
                                        class="text-[10px] font-bold bg-slate-100 text-slate-400 px-3 py-2 rounded-lg uppercase tracking-wide">Read
                                        Only</span>
                                @else
                                    <button
                                        onclick="document.getElementById('edit-modal-{{ $trx->id }}').classList.remove('hidden')"
                                        class="px-4 py-2 bg-amber-50 hover:bg-amber-100 text-amber-600 rounded-lg text-xs font-bold uppercase tracking-wide active:scale-95 transition-all shadow-sm">Edit</button>
                                    <form action="{{ route('laporan-bank.destroy', $trx->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-bold uppercase tracking-wide active:scale-95 transition-all shadow-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- VIEW DESKTOP: Tabel Modern --}}
                <div class="hidden lg:block overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr
                                class="bg-slate-50/80 border-b border-slate-200 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-left">
                                <th class="px-5 py-4 text-center w-12">No</th>
                                <th class="px-5 py-4">Waktu</th>
                                <th class="px-5 py-4">Jenis Transaksi</th>
                                <th class="px-5 py-4">Bank</th>
                                <th class="px-5 py-4 text-right">Nominal</th>
                                <th class="px-5 py-4 text-right">Bayar</th>
                                <th class="px-5 py-4 text-right">Laba Bersih</th>
                                <th class="px-5 py-4 text-right">Saldo Akhir</th>
                                <th class="px-5 py-4 text-right">Saldo Kas</th>
                                <th class="px-5 py-4">Keterangan</th>
                                <th class="px-5 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium">
                            @foreach ($transaksis as $index => $trx)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-5 py-3.5 text-center text-slate-500 font-extrabold">{{ $index + 1 }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-700">
                                        <span
                                            class="font-bold">{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('d-m-Y') }}</span>
                                        <span
                                            class="text-xs text-slate-400 ml-1.5">{{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i') }}</span>
                                    </td>
                                    <td class="px-5 py-3.5 font-bold text-slate-900">
                                        {{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span
                                            class="inline-block px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200/60 shadow-sm">
                                            {{ $trx->bank->nama_bank ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right text-slate-800">Rp
                                        {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3.5 text-right text-slate-500">Rp
                                        {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}</td>
                                    <td
                                        class="px-5 py-3.5 text-right font-extrabold {{ ($trx->laba_bersih ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        Rp {{ number_format(abs($trx->laba_bersih ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-5 py-3.5 text-right font-bold text-blue-700 bg-blue-50/40 border-l border-r border-blue-50/50">
                                        Rp {{ number_format($trx->saldo_akhir_dynamic ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td
                                        class="px-5 py-3.5 text-right font-bold {{ ($trx->saldo_kas ?? 0) >= 0 ? 'text-emerald-700 bg-emerald-50/30' : 'text-rose-600 bg-rose-50/30' }}">
                                        Rp {{ number_format(abs($trx->saldo_kas ?? 0), 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-600 max-w-[180px] truncate"
                                        title="{{ $trx->keterangan }}">{{ $trx->keterangan ?? '-' }}</td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-center gap-2">
                                            @if (Auth::user()->role === 'super_admin')
                                                <span
                                                    class="text-[10px] text-slate-400 font-bold bg-slate-100 px-2 py-1 rounded uppercase">Read
                                                    Only</span>
                                            @else
                                                <button
                                                    onclick="document.getElementById('edit-modal-{{ $trx->id }}').classList.remove('hidden')"
                                                    class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition shadow-sm"
                                                    title="Edit"><svg class="w-4 h-4" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232a2 2 0 112.828 2.828L11.828 13.828a2 2 0 01-1.414.586H9v-2z" />
                                                    </svg></button>
                                                <form action="{{ route('laporan-bank.destroy', $trx->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus transaksi ini? Data saldo akan dikalkulasi ulang.')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition shadow-sm"
                                                        title="Hapus"><svg class="w-4 h-4" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                                        </svg></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-14 flex flex-col items-center justify-center text-slate-500 bg-slate-50/50">
                    <div
                        class="w-16 h-16 bg-white rounded-full shadow-sm border border-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-base font-bold text-slate-700">Belum ada transaksi</p>
                    <p class="text-xs font-medium text-slate-400 mt-1 text-center px-4">
                        {{ request('cabang_id') && request('user_id') ? 'Tidak ada transaksi pada tanggal tersebut.' : 'Silakan pilih Cabang dan Akun terlebih dahulu.' }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL EDIT --}}
    @foreach ($transaksis as $trx)
        <div id="edit-modal-{{ $trx->id }}" tabindex="-1"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4"
            onclick="if(event.target === this) this.classList.add('hidden')">

            <div
                class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-5 sm:p-8 w-full max-w-lg animate-in fade-in zoom-in duration-200">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm sm:text-base font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Transaksi #{{ $trx->id }}
                    </h3>
                    <button type="button"
                        onclick="document.getElementById('edit-modal-{{ $trx->id }}').classList.add('hidden')"
                        class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition active:scale-95">&times;</button>
                </div>

                <div class="max-h-[75vh] overflow-y-auto pr-1">
                    <form action="{{ route('laporan-bank.update', $trx->id) }}" method="POST"
                        onsubmit="return submitEditForm(this)" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Bank
                                Terkait</label>
                            <select name="bank_id"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition appearance-none cursor-pointer"
                                required>
                                @foreach ($dataBanks as $bank)
                                    <option value="{{ $bank->id }}"
                                        {{ $trx->bank_id == $bank->id ? 'selected' : '' }}>
                                        {{ $bank->nama_bank }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Jenis
                                Transaksi</label>
                            <select name="jenis_transaksi_id"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition appearance-none cursor-pointer"
                                required>
                                <option value="">Pilih Jenis Transaksi</option>
                                @foreach ($jenisTransaksis as $jenis)
                                    <option value="{{ $jenis->id }}"
                                        {{ $trx->jenis_transaksi_id == $jenis->id ? 'selected' : '' }}>
                                        {{ $jenis->nama_transaksi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nominal</label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 text-sm">Rp</span>
                                    <input type="text" inputmode="numeric" name="nominal"
                                        value="{{ number_format($trx->nominal, 0, ',', '.') }}"
                                        class="currency-input-edit font-bold w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                        required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Uang
                                    Bayar</label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 text-sm">Rp</span>
                                    <input type="text" inputmode="numeric" name="bayar"
                                        value="{{ number_format($trx->bayar, 0, ',', '.') }}"
                                        class="currency-input-edit font-bold w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Keterangan
                                <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="keterangan" value="{{ $trx->keterangan }}"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"
                                placeholder="Tulis keterangan singkat">
                        </div>

                        <div class="pt-3 flex gap-2.5 justify-end mt-2">
                            <button type="button"
                                onclick="document.getElementById('edit-modal-{{ $trx->id }}').classList.add('hidden')"
                                class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition active:scale-95">Batal</button>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all active:scale-95">Simpan
                                Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Script JavaScript --}}
    <script>
        function toggleFilter() {
            const filterBody = document.getElementById('filterBody');
            const filterIcon = document.getElementById('filterIcon');

            if (filterBody.classList.contains('hidden')) {
                filterBody.classList.remove('hidden');
                filterBody.classList.add('block');
                filterIcon.classList.add('rotate-180');
            } else {
                filterBody.classList.add('hidden');
                filterBody.classList.remove('block');
                filterIcon.classList.remove('rotate-180');
            }
        }

        document.querySelectorAll('.currency-input-edit').forEach(function(input) {
            input.addEventListener('input', function() {
                let raw = this.value.replace(/\D/g, '');
                this.value = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
            });
        });

        function submitEditForm(form) {
            form.querySelectorAll('.currency-input-edit').forEach(function(input) {
                input.value = input.value.replace(/\D/g, '');
            });
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cabangSelect = document.getElementById('cabang_id');
            const userSelect = document.getElementById('user_id');
            const selectedUser = '{{ request('user_id') }}';

            function loadUsers(cabangId) {
                userSelect.innerHTML = '<option value="">-- Pilih Akun --</option>';
                if (!cabangId) return;
                fetch(`/laporan-bank-admin/get-users-by-cabang/${cabangId}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(user => {
                            const opt = document.createElement('option');
                            opt.value = user.id;
                            opt.textContent = user.name;
                            if (user.id == selectedUser) opt.selected = true;
                            userSelect.appendChild(opt);
                        });
                    });
            }

            cabangSelect.addEventListener('change', function() {
                loadUsers(this.value);
            });

            if (cabangSelect.value) loadUsers(cabangSelect.value);
        });
    </script>
@endsection
