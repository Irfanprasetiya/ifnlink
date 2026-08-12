@extends('layouts.app')

@section('title', 'Transaksi Bank')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12 mt-5">

        {{-- Notifikasi Limit Transaksi --}}
        @auth
            @php
                $tenant = Auth::user()->tenant;
                $maxTransaksi = $tenant->plan && $tenant->plan->harga == 0 ? 10 : 9999;
                $todayTransaksi = App\Models\TransaksiBank::where('tenant_id', $tenant->id_tenant)
                    ->whereDate('waktu_transaksi', now()->toDateString())
                    ->count();
                $todayCount = floor($todayTransaksi / 2);
                $canAddTransaksi = $todayCount < $maxTransaksi;
            @endphp
        @endauth

        @if (!$canAddTransaksi)
            <div
                class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-xl text-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center shrink-0 text-amber-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <span>Paket Gratis hanya <strong>10 transaksi/hari</strong>. Upgrade ke PRO untuk transaksi tanpa
                        batas!</span>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="inline-flex items-center justify-center bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition-colors whitespace-nowrap">
                    Upgrade Sekarang &rarr;
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0)
            <div
                class="bg-blue-50 border border-blue-200 text-blue-800 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    Paket Gratis: <strong>{{ $todayCount }}/10</strong> transaksi hari ini.
                    @if ($maxTransaksi - $todayCount <= 3 && $canAddTransaksi)
                        <span class="text-amber-600 font-bold ml-1">Sisa {{ $maxTransaksi - $todayCount }} transaksi
                            lagi!</span>
                    @endif
                </div>
            </div>
        @endif

        {{-- Header & Tombol Aksi --}}
        <div
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                        <span class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </span>
                        Transaksi Saldo
                    </h1>
                    {{-- Deskripsi disembunyikan di HP (hidden sm:block) --}}
                    <p class="text-sm text-slate-500 mt-2 font-medium hidden sm:block">Kelola kasir, mutasi bank,
                        penambahan, dan pengurangan saldo toko.</p>
                </div>

                {{-- Tombol Aksi di HP dibuat grid 3 kolom sejajar agar hemat tempat --}}
                <div class="grid grid-cols-3 sm:flex sm:flex-nowrap gap-2 sm:gap-3 w-full sm:w-auto">
                    <button type="button" onclick="openModal('penambahan')"
                        class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm shadow-sm transition-all"
                        title="Tambah Saldo">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Tambah</span>
                    </button>
                    <button type="button" onclick="openModal('pengeluaran')"
                        class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 bg-rose-600 hover:bg-rose-700 text-white font-medium px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm shadow-sm transition-all"
                        title="Kurangi Saldo">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                        </svg>
                        <span>Kurang</span>
                    </button>
                    <button type="button"
                        onclick="document.getElementById('modalTransferSaldo').classList.remove('hidden')"
                        class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-2 sm:px-4 py-2 sm:py-2.5 rounded-lg text-xs sm:text-sm shadow-sm transition-all"
                        title="Oper Saldo">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Oper</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Alert Success / Error --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-4 rounded-xl text-sm shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-bold text-rose-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                    Terdapat Kesalahan:
                </div>
                <ul class="list-disc pl-7 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Filter Box (Dibuat Collapsible di Mobile) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-2">
            {{-- Tombol Toggle Filter (Hanya terlihat di HP) --}}
            <button type="button" onclick="toggleFilter()"
                class="w-full flex md:hidden items-center justify-between p-4 bg-slate-50 text-slate-700 hover:bg-slate-100 transition-colors">
                <span class="font-bold text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Pencarian & Filter Data
                </span>
                <svg id="filterIcon" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Body Filter (Disembunyikan di HP secara default, muncul di layar besar) --}}
            <div id="filterBody" class="hidden md:block p-5 border-t border-slate-200 md:border-t-0">
                <form method="GET" action="{{ route('trx-bank.index') }}"
                    class="flex flex-col md:flex-row flex-wrap gap-4 items-end">
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label for="tanggal" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            value="{{ request('tanggal', now()->toDateString()) }}"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-colors">
                    </div>
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label for="cabang_id" class="block text-sm font-medium text-slate-700 mb-1.5">Cabang</label>
                        <select name="cabang_id" id="cabang_id"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-colors">
                            <option value="">-- Semua Cabang --</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}" {{ $cabang_id == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label for="user_id" class="block text-sm font-medium text-slate-700 mb-1.5">Akun</label>
                        <select name="user_id" id="user_id"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 transition-colors">
                            <option value="">Pilih Akun</option>
                        </select>
                    </div>
                    <div class="w-full md:w-auto mt-2 md:mt-0">
                        <button type="submit"
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-2.5 rounded-lg text-sm shadow-sm transition-all">
                            Filter Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data Bank --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                            <th class="px-6 py-4 w-16 text-center">#</th>
                            <th class="px-6 py-4">Nama Bank / Kas</th>
                            <th class="px-6 py-4 text-right">Saldo Saat Ini</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $no = 1; @endphp
                        @forelse ($banks as $bank)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <td class="px-6 py-4 text-center text-slate-500 font-medium">{{ $no++ }}</td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs border border-blue-100 shrink-0">
                                            {{ substr($bank->nama_bank, 0, 1) }}
                                        </div>
                                        <span class="font-bold text-slate-800">{{ $bank->nama_bank }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-right font-bold text-slate-800 text-base">
                                    Rp {{ number_format($saldoTotal[strtolower($bank->nama_bank)] ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if (($statusBank[$bank->id] ?? 'Disable') === 'Active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 border border-slate-200">
                                            Disable
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada data bank tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL TAMBAH/KURANG SALDO                  --}}
        {{-- ========================================== --}}
        <div id="modalTambahSaldo" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden transform transition-all">
                <div class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2" id="modalTambahSaldoTitle">
                        Tambah Saldo
                    </h3>
                    <button type="button" onclick="document.getElementById('modalTambahSaldo').classList.add('hidden')"
                        class="text-slate-400 hover:text-rose-600 bg-white hover:bg-rose-50 rounded-lg p-1.5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('trx-bank.store') }}" method="POST" id="formTambahSaldo" class="p-5 space-y-4">
                    @csrf
                    <input type="hidden" name="jenis_transaksi" id="jenis_transaksi" value="penambahan">
                    <input type="hidden" name="cabang_id" id="modal_cabang_id_hidden">

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">User dan Cabang</label>
                        <select name="user_id" id="modal_user_id" required
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors">
                            <option value="">-- Pilih User --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" data-cabang-id="{{ $user->cabang_id }}">
                                    {{ $user->name }} ({{ $user->cabang->nama_cabang ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih Bank / Kas</label>
                        <select id="modal_bank_id" name="bank_id" required
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors">
                            <option value="">-- Pilih Bank --</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="kategoriPengeluaranBox" class="hidden">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori Pengeluaran</label>
                        <select name="akun_pengeluaran_id" id="modal_akun_pengeluaran_id"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($akunPengeluaran as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->nama_akun }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal (Rp)</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 font-medium">Rp</span>
                            <input id="nominal_input" type="text" inputmode="numeric" name="nominal" required
                                class="currency-input w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block pl-10 p-2.5 outline-none transition-colors font-bold"
                                placeholder="0">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan (Opsional)</label>
                        <input id="keterangan_input" name="keterangan" type="text"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors"
                            placeholder="Tulis keterangan transaksi...">
                    </div>

                    <div class="pt-3">
                        <button type="submit" id="btnSimpan"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md shadow-blue-500/20 transition-all focus:ring-4 focus:ring-blue-300 outline-none">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- MODAL TRANSFER ANTAR CABANG                --}}
        {{-- ========================================== --}}
        <div id="modalTransferSaldo" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
            <div
                class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all">
                <div
                    class="flex justify-between items-center p-5 border-b border-slate-100 bg-slate-50/50 sticky top-0 z-10">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        Transfer Antar Cabang
                    </h3>
                    <button type="button" onclick="document.getElementById('modalTransferSaldo').classList.add('hidden')"
                        class="text-slate-400 hover:text-rose-600 bg-white hover:bg-rose-50 rounded-lg p-1.5 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('trx-bank.transfer') }}" method="POST" id="formTransferSaldo"
                    class="p-5 space-y-5">
                    @csrf

                    {{-- Sumber --}}
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-4">
                        <div
                            class="flex items-center gap-2 text-slate-700 font-bold text-sm border-b border-slate-200 pb-2">
                            <span
                                class="w-5 h-5 rounded bg-rose-100 text-rose-600 flex items-center justify-center text-xs">1</span>
                            Data Sumber (Asal Dana)
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">User dan Cabang Asal</label>
                            <select name="source_user_id" required
                                class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors">
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                        ({{ $user->cabang->nama_cabang ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bank / Kas Asal</label>
                                <select name="source_bank_id" required
                                    class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nominal Keluar</label>
                                <input type="text" inputmode="numeric" name="nominal_keluar" id="nominal_keluar"
                                    required
                                    class="currency-input w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none font-bold"
                                    placeholder="Rp 0">
                            </div>
                        </div>
                    </div>

                    {{-- Icon Panah --}}
                    <div class="flex justify-center -my-3 relative z-10">
                        <div class="bg-white border border-slate-200 rounded-full p-2 text-blue-500 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>

                    {{-- Tujuan --}}
                    <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl space-y-4">
                        <div class="flex items-center gap-2 text-blue-800 font-bold text-sm border-b border-blue-200 pb-2">
                            <span
                                class="w-5 h-5 rounded bg-blue-100 text-blue-600 flex items-center justify-center text-xs">2</span>
                            Data Tujuan (Penerima Dana)
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">User dan Cabang Tujuan</label>
                            <select name="dest_user_id" required
                                class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors">
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                        ({{ $user->cabang->nama_cabang ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Bank / Kas Tujuan</label>
                                <select name="dest_bank_id" required
                                    class="w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Nominal Masuk</label>
                                <input type="text" inputmode="numeric" name="nominal_masuk" id="nominal_masuk"
                                    class="currency-input w-full bg-white border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none font-bold"
                                    placeholder="Biarkan jika sama">
                            </div>
                        </div>
                    </div>

                    {{-- Box Selisih / Biaya Admin --}}
                    <div id="selisihBox"
                        class="hidden bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-xl px-4 py-3 flex justify-between items-center shadow-sm font-medium">
                        <span>Biaya Admin (Selisih):</span>
                        <span id="selisihValue" class="font-bold text-amber-700 bg-white px-2 py-1 rounded shadow-sm">Rp
                            0</span>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan Transfer
                            (Opsional)</label>
                        <input type="text" name="keterangan"
                            class="w-full bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 outline-none transition-colors"
                            placeholder="Tulis alasan oper saldo...">
                    </div>

                    <div class="pt-2 sticky bottom-0 bg-white pb-2">
                        <button type="submit" id="btnSimpanTransfer"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md shadow-blue-500/20 transition-all focus:ring-4 focus:ring-blue-300 outline-none">
                            Proses Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Tambahkan di dalam blok <script> Anda
        function toggleFilter() {
            const filterBody = document.getElementById('filterBody');
            const filterIcon = document.getElementById('filterIcon');

            // Toggle visibility (hapus hidden, tambahkan block, atau sebaliknya)
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

        function formatCurrencyInput(inputElement) {
            inputElement.addEventListener('input', function() {
                let cursorPos = this.selectionStart;
                let originalLength = this.value.length;
                let raw = this.value.replace(/\D/g, '');
                let formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
                this.value = formatted;
                let newLength = this.value.length;
                let diff = newLength - originalLength;
                this.selectionStart = this.selectionEnd = cursorPos + diff;
            });
        }

        function parseCurrency(value) {
            return parseFloat((value || '').replace(/\D/g, '')) || 0;
        }

        function openModal(type) {
            const modal = document.getElementById('modalTambahSaldo');
            modal.classList.remove('hidden');
            document.getElementById('jenis_transaksi').value = type;
            document.getElementById('modalTambahSaldoTitle').innerText = type === 'penambahan' ? 'Tambah Saldo' :
                'Kurangi Saldo';

            const kategoriBox = document.getElementById('kategoriPengeluaranBox');
            const kategoriSelect = document.getElementById('modal_akun_pengeluaran_id');

            if (type === 'pengeluaran') {
                kategoriBox.classList.remove('hidden');
                kategoriSelect.setAttribute('required', 'required');
            } else {
                kategoriBox.classList.add('hidden');
                kategoriSelect.removeAttribute('required');
                kategoriSelect.value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.currency-input').forEach(function(input) {
                formatCurrencyInput(input);
            });

            const cabangSelect = document.getElementById('cabang_id');
            const userSelect = document.getElementById('user_id');
            const selectedUserId = "{{ request('user_id') }}";

            function fetchUsers(cabangId, currentUserId = null) {
                if (!cabangId) return;
                fetch(`/get-users-by-cabang/${cabangId}`)
                    .then(response => response.json())
                    .then(data => {
                        userSelect.innerHTML = '<option value="">-- Pilih Akun --</option>';
                        data.forEach(user => {
                            const isSelected = (user.id == currentUserId) ? 'selected' : '';
                            userSelect.innerHTML +=
                                `<option value="${user.id}" ${isSelected}>${user.name}</option>`;
                        });
                    });
            }
            if (cabangSelect.value) fetchUsers(cabangSelect.value, selectedUserId);
            cabangSelect.addEventListener('change', function() {
                fetchUsers(this.value);
            });

            const modalUserSelect = document.getElementById('modal_user_id');
            const cabangHidden = document.getElementById('modal_cabang_id_hidden');
            modalUserSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                cabangHidden.value = selected.getAttribute('data-cabang-id') || '';
            });

            const keluar = document.getElementById('nominal_keluar');
            const masuk = document.getElementById('nominal_masuk');
            const box = document.getElementById('selisihBox');
            const value = document.getElementById('selisihValue');

            function updateSelisih() {
                const k = parseCurrency(keluar.value);
                const m = parseCurrency(masuk.value);
                if (k > 0 && m > 0) {
                    const selisih = k - m;
                    box.classList.remove('hidden');
                    value.textContent = 'Rp ' + selisih.toLocaleString('id-ID');

                    if (selisih < 0) {
                        box.classList.add('bg-rose-50', 'border-rose-200', 'text-rose-800');
                        box.classList.remove('bg-amber-50', 'border-amber-200', 'text-amber-800');
                        value.classList.add('text-rose-700');
                        value.classList.remove('text-amber-700');
                    } else {
                        box.classList.add('bg-amber-50', 'border-amber-200', 'text-amber-800');
                        box.classList.remove('bg-rose-50', 'border-rose-200', 'text-rose-800');
                        value.classList.add('text-amber-700');
                        value.classList.remove('text-rose-700');
                    }
                } else if (k > 0 && m == 0) {
                    box.classList.add('hidden');
                } else {
                    box.classList.add('hidden');
                }
            }

            keluar.addEventListener('input', function() {
                if (!masuk.value) masuk.value = this.value;
                updateSelisih();
            });
            masuk.addEventListener('input', updateSelisih);

            // Handle Submit Form Tambah
            document.getElementById('formTambahSaldo').addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                if (btn.dataset.submitting === 'true') {
                    e.preventDefault();
                    return;
                }
                btn.dataset.submitting = 'true';
                this.querySelectorAll('.currency-input').forEach(i => i.value = i.value.replace(/\D/g, ''));

                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                btn.innerHTML =
                    '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
            });

            // Handle Submit Form Transfer
            document.getElementById('formTransferSaldo').addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                if (btn.dataset.submitting === 'true') {
                    e.preventDefault();
                    return;
                }
                btn.dataset.submitting = 'true';
                this.querySelectorAll('.currency-input').forEach(i => i.value = i.value.replace(/\D/g, ''));

                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                btn.innerHTML =
                    '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            });
        });
    </script>
@endsection
