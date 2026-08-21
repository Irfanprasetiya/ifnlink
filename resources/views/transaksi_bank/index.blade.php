@extends('layouts.app')

@section('title', 'Transaksi Bank')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-4 sm:space-y-6 pb-12 mt-3 sm:mt-5">
        {{-- Notifikasi Limit Transaksi (Logika TIDAK diubah) --}}
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
                class="bg-amber-50 border border-amber-200 text-amber-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm relative overflow-hidden">
                <div class="flex items-start sm:items-center gap-2.5 sm:gap-3 relative z-10">
                    <div class="p-1.5 sm:p-2 bg-amber-100/80 rounded-lg text-amber-600 shrink-0 mt-0.5 sm:mt-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <p class="text-[11px] sm:text-sm font-medium leading-relaxed">
                        Paket Gratis hanya <strong>10 transaksi/hari</strong>. <span class="hidden sm:inline">Upgrade ke PRO
                            untuk transaksi tanpa batas!</span>
                    </p>
                </div>
                <a href="{{ route('upgrade') }}"
                    class="inline-flex items-center justify-center w-full sm:w-auto bg-amber-600 hover:bg-amber-700 text-white text-[11px] sm:text-sm font-bold px-3 py-2 sm:px-4 sm:py-2 rounded-lg transition-colors relative z-10 active:scale-95">
                    Upgrade Sekarang <span class="ml-1 sm:hidden">→</span>
                </a>
            </div>
        @endif

        @if ($tenant->plan && $tenant->plan->harga == 0)
            <div
                class="bg-blue-50/80 border border-blue-100 text-blue-800 px-4 sm:px-5 py-3 sm:py-3.5 rounded-2xl flex items-start sm:items-center gap-2.5 sm:gap-3 shadow-sm">
                <div class="p-1.5 sm:p-2 bg-blue-100/80 rounded-lg text-blue-600 shrink-0 mt-0.5 sm:mt-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-[11px] sm:text-sm font-medium leading-relaxed">
                    Paket Gratis: <strong>{{ $todayCount }}/10</strong> transaksi hari ini.
                    @if ($maxTransaksi - $todayCount <= 3 && $canAddTransaksi)
                        <span class="text-amber-600 font-bold ml-1">Sisa {{ $maxTransaksi - $todayCount }} transaksi
                            lagi!</span>
                    @endif
                </p>
            </div>
        @endif

        {{-- Header & Tombol Aksi --}}
        <div
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4 w-full">
                <div>
                    <h1 class="text-lg sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2">
                        <span
                            class="p-1.5 sm:p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0 text-sm sm:text-base">💳</span>
                        Transaksi Saldo
                    </h1>
                    <p class="text-[10px] sm:text-sm text-slate-500 mt-1 font-medium hidden sm:block">
                        Kelola kasir, mutasi bank, penambahan, dan pengurangan saldo toko.
                    </p>
                </div>

                <div class="grid grid-cols-3 sm:flex sm:flex-nowrap gap-2 sm:gap-3 w-full sm:w-auto">
                    <button type="button" onclick="openModal('penambahan')"
                        class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-2 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95"
                        title="Tambah Saldo">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span>Tambah</span>
                    </button>
                    <button type="button" onclick="openModal('pengeluaran')"
                        class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-1.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold px-2 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95"
                        title="Kurangi Saldo">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 12H6" />
                        </svg>
                        <span>Kurang</span>
                    </button>
                    <button type="button"
                        onclick="document.getElementById('modalTransferSaldo').classList.remove('hidden')"
                        class="flex flex-col sm:flex-row items-center justify-center gap-1 sm:gap-1.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-2 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm shadow-sm transition-all active:scale-95"
                        title="Oper Saldo">
                        <svg class="w-4 h-4 sm:w-4 sm:h-4 shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span>Oper</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
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
            </div>
        @endif
        @if ($errors->any())
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-4 sm:px-5 py-3 sm:py-4 rounded-2xl text-[11px] sm:text-sm shadow-sm">
                <div class="flex items-center gap-2 mb-2 font-bold text-rose-700">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
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

        {{-- Filter Box --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-2">
            <button type="button" onclick="toggleFilter()"
                class="w-full flex md:hidden items-center justify-between p-4 bg-slate-50/50 text-slate-700 hover:bg-slate-100 transition-colors">
                <span class="font-bold text-xs sm:text-sm flex items-center gap-2">
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
            <div id="filterBody" class="hidden md:block p-4 sm:p-5 border-t border-slate-100 md:border-t-0">
                <form method="GET" action="{{ route('trx-bank.index') }}"
                    class="flex flex-col md:flex-row flex-wrap gap-3 sm:gap-4 items-end">
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label for="tanggal"
                            class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1.5">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            value="{{ request('tanggal', now()->toDateString()) }}"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-2.5 sm:p-2.5 transition-all outline-none">
                    </div>
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label for="cabang_id"
                            class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1.5">Cabang</label>
                        <select name="cabang_id" id="cabang_id"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-2.5 sm:p-2.5 transition-all outline-none">
                            <option value="">-- Semua Cabang --</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->id }}" {{ $cabang_id == $cabang->id ? 'selected' : '' }}>
                                    {{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label for="user_id"
                            class="block text-[11px] sm:text-sm font-semibold text-slate-700 mb-1.5">Akun</label>
                        <select name="user_id" id="user_id"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-2.5 sm:p-2.5 transition-all outline-none">
                            <option value="">Pilih Akun</option>
                        </select>
                    </div>
                    <div class="w-full md:w-auto mt-2 md:mt-0">
                        <button type="submit"
                            class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 sm:py-2.5 rounded-xl text-sm shadow-sm transition-all active:scale-95">Filter
                            Data</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Data Bank --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-4 sm:px-6 py-3 sm:py-4 w-12 sm:w-16 text-center hidden sm:table-cell">#</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4">Nama Bank / Kas</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-center hidden sm:table-cell">Status</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-right">Saldo Saat Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                        @php $no = 1; @endphp
                        @forelse ($banks as $bank)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-center text-slate-500 font-medium hidden sm:table-cell">
                                    {{ $no++ }}
                                </td>

                                {{-- Kolom Nama Bank: Menggabungkan Status di Mobile --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4">
                                    <div class="flex items-center gap-2.5 sm:gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xs sm:text-sm border border-blue-100 shrink-0">
                                            {{ substr($bank->nama_bank, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-800 block">{{ $bank->nama_bank }}</span>

                                            {{-- Status Badge (Muncul di Mobile) --}}
                                            <div class="sm:hidden mt-0.5">
                                                @if (($statusBank[$bank->id] ?? 'Disable') === 'Active')
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">Disable</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status Badge (Muncul di PC/Tablet) --}}
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-center hidden sm:table-cell">
                                    @if (($statusBank[$bank->id] ?? 'Disable') === 'Active')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border border-emerald-200">Active</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-slate-100 text-slate-500 border border-slate-200">Disable</span>
                                    @endif
                                </td>

                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 text-right font-extrabold text-slate-800 text-sm sm:text-base">
                                    Rp {{ number_format($saldoTotal[strtolower($bank->nama_bank)] ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 text-xs sm:text-sm">Belum ada
                                    data bank tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL TAMBAH/KURANG --}}
        <div id="modalTambahSaldo"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
            <div
                class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in-95 duration-200 my-auto">
                <div
                    class="flex justify-between items-center px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800" id="modalTambahSaldoTitle">Tambah Saldo</h3>
                    <button type="button" onclick="document.getElementById('modalTambahSaldo').classList.add('hidden')"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form action="{{ route('trx-bank.store') }}" method="POST" id="formTambahSaldo"
                    class="p-5 sm:p-6 space-y-4" onsubmit="return confirmSubmit()">
                    @csrf
                    <input type="hidden" name="jenis_transaksi" id="jenis_transaksi" value="penambahan">
                    <input type="hidden" name="cabang_id" id="modal_cabang_id_hidden">

                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">User
                            dan Cabang</label>
                        <select name="user_id" id="modal_user_id" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all">
                            <option value="">-- Pilih User --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" data-cabang-id="{{ $user->cabang_id }}">
                                    {{ $user->name }} ({{ $user->cabang->nama_cabang ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Pilih
                            Bank / Kas</label>
                        <select id="modal_bank_id" name="bank_id" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all">
                            <option value="">-- Pilih Bank --</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="kategoriPengeluaranBox" class="hidden">
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Kategori
                            Pengeluaran <span class="text-rose-500">*</span></label>
                        <select name="akun_pengeluaran_id" id="modal_akun_pengeluaran_id" required
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($akunPengeluaran as $akun)
                                <option value="{{ $akun->id }}">{{ $akun->nama_akun }}</option>
                            @endforeach
                        </select>
                        <p class="text-[10px] sm:text-xs text-rose-500 mt-1.5 font-medium hidden" id="errorAkun">⚠️ Wajib
                            pilih kategori pengeluaran!</p>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nominal
                            (Rp)</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 font-bold">Rp</span>
                            <input id="nominal_input" type="text" inputmode="numeric" name="nominal" required
                                class="currency-input w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block pl-10 pr-4 py-3 sm:p-2.5 font-bold outline-none transition-all"
                                placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Keterangan
                            <span class="font-normal capitalize text-slate-400">(Opsional)</span></label>
                        <input id="keterangan_input" name="keterangan" type="text"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all"
                            placeholder="Tulis keterangan...">
                    </div>
                    <div class="pt-4 pb-2">
                        <button type="submit" id="btnSimpan"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL TRANSFER --}}
        <div id="modalTransferSaldo"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 sm:p-0">
            <div
                class="bg-white rounded-2xl sm:rounded-3xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto animate-in fade-in zoom-in-95 duration-200">
                <div
                    class="flex justify-between items-center px-5 py-4 sm:px-6 sm:py-5 border-b border-slate-100 bg-slate-50/50 sticky top-0 z-20 backdrop-blur-md">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Transfer Antar Cabang</h3>
                    <button type="button" onclick="document.getElementById('modalTransferSaldo').classList.add('hidden')"
                        class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:bg-slate-200 hover:text-rose-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('trx-bank.transfer') }}" method="POST" id="formTransferSaldo"
                    class="p-5 sm:p-6 space-y-5">
                    @csrf

                    {{-- Sumber --}}
                    <div class="p-4 sm:p-5 bg-slate-50 border border-slate-200 rounded-2xl space-y-4 relative">
                        <div
                            class="flex items-center gap-2.5 text-slate-700 font-bold text-[11px] sm:text-sm border-b border-slate-200 pb-2.5">
                            <span
                                class="w-5 h-5 rounded flex items-center justify-center text-xs bg-rose-100 text-rose-600">1</span>
                            Data Sumber (Asal Dana)
                        </div>
                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">User
                                Asal</label>
                            <select name="source_user_id" required
                                class="w-full bg-white border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all">
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                        ({{ $user->cabang->nama_cabang ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Bank
                                    Asal</label>
                                <select name="source_bank_id" required
                                    class="w-full bg-white border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nominal
                                    Keluar</label>
                                <input type="text" inputmode="numeric" name="nominal_keluar" id="nominal_keluar"
                                    required
                                    class="currency-input w-full bg-white border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 font-bold outline-none transition-all"
                                    placeholder="Rp 0">
                            </div>
                        </div>
                    </div>

                    {{-- Icon Panah --}}
                    <div class="flex justify-center -my-3 relative z-10">
                        <div class="bg-white border border-slate-200 rounded-full p-2.5 text-blue-500 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </div>
                    </div>

                    {{-- Tujuan --}}
                    <div class="p-4 sm:p-5 bg-blue-50/50 border border-blue-100 rounded-2xl space-y-4">
                        <div
                            class="flex items-center gap-2.5 text-blue-800 font-bold text-[11px] sm:text-sm border-b border-blue-200 pb-2.5">
                            <span
                                class="w-5 h-5 rounded flex items-center justify-center text-xs bg-blue-200 text-blue-700">2</span>
                            Data Tujuan (Penerima Dana)
                        </div>
                        <div>
                            <label
                                class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">User
                                Tujuan</label>
                            <select name="dest_user_id" required
                                class="w-full bg-white border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all">
                                <option value="">-- Pilih User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}
                                        ({{ $user->cabang->nama_cabang ?? '-' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Bank
                                    Tujuan</label>
                                <select name="dest_bank_id" required
                                    class="w-full bg-white border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all">
                                    <option value="">-- Pilih Bank --</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nominal
                                    Masuk</label>
                                <input type="text" inputmode="numeric" name="nominal_masuk" id="nominal_masuk"
                                    class="currency-input w-full bg-white border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 font-bold outline-none transition-all"
                                    placeholder="Biarkan jika sama">
                            </div>
                        </div>
                    </div>

                    {{-- Selisih Box --}}
                    <div id="selisihBox"
                        class="hidden bg-amber-50 border border-amber-200 text-amber-800 text-[11px] sm:text-sm rounded-xl px-4 py-3 flex justify-between items-center shadow-sm font-medium">
                        <span>Biaya Admin (Selisih):</span>
                        <span id="selisihValue"
                            class="font-bold text-amber-700 bg-white px-2.5 py-1 rounded-md shadow-sm border border-amber-100">Rp
                            0</span>
                    </div>

                    <div>
                        <label
                            class="block text-[11px] sm:text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Keterangan
                            Transfer <span class="font-normal capitalize text-slate-400">(Opsional)</span></label>
                        <input type="text" name="keterangan"
                            class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-base sm:text-sm rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 px-4 py-3 sm:p-2.5 outline-none transition-all"
                            placeholder="Tulis alasan oper saldo...">
                    </div>

                    <div class="pt-4 pb-2 sticky bottom-0 bg-white border-t border-slate-50 sm:border-transparent mt-4">
                        <button type="submit" id="btnSimpanTransfer"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm shadow-sm transition-all active:scale-95 text-center">
                            Proses Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- SCRIPT JAVASCRIPT LOGIKA UTUH TIDAK DIUBAH --}}
    <script>
        function toggleFilter() {
            const fb = document.getElementById('filterBody'),
                fi = document.getElementById('filterIcon');
            if (fb.classList.contains('hidden')) {
                fb.classList.remove('hidden');
                fb.classList.add('block');
                fi.classList.add('rotate-180');
            } else {
                fb.classList.add('hidden');
                fb.classList.remove('block');
                fi.classList.remove('rotate-180');
            }
        }

        function formatCurrencyInput(el) {
            el.addEventListener('input', function() {
                let p = this.selectionStart,
                    l = this.value.length,
                    r = this.value.replace(/\D/g, '');
                this.value = r ? new Intl.NumberFormat('id-ID').format(r) : '';
                this.selectionStart = this.selectionEnd = p + (this.value.length - l);
            });
        }

        function parseCurrency(v) {
            return parseFloat((v || '').replace(/\D/g, '')) || 0;
        }

        // ✅ Fungsi openModal (HANYA SATU)
        function openModal(type) {
            const modal = document.getElementById('modalTambahSaldo');
            const jenisInput = document.getElementById('jenis_transaksi');
            const title = document.getElementById('modalTambahSaldoTitle');
            const kategoriBox = document.getElementById('kategoriPengeluaranBox');
            const akunSelect = document.getElementById('modal_akun_pengeluaran_id');
            const errorAkun = document.getElementById('errorAkun');

            modal.classList.remove('hidden');
            jenisInput.value = type;
            title.textContent = type === 'penambahan' ? 'Tambah Saldo' : 'Kurangi Saldo';

            if (type === 'pengeluaran') {
                kategoriBox.classList.remove('hidden');
                akunSelect.setAttribute('required', 'required');
            } else {
                kategoriBox.classList.add('hidden');
                akunSelect.removeAttribute('required');
                akunSelect.value = '';
                errorAkun.classList.add('hidden');
            }
        }

        // ✅ Konfirmasi sebelum submit
        function confirmSubmit() {
            const jenis = document.getElementById('jenis_transaksi').value;
            const akun = document.getElementById('modal_akun_pengeluaran_id');
            const akunNama = akun.options[akun.selectedIndex]?.text || '';
            const nominal = document.getElementById('nominal_input').value;

            if (jenis === 'pengeluaran') {
                return confirm(
                    `⚠️ Konfirmasi Pengeluaran\n\n` +
                    `Kategori: ${akunNama}\n` +
                    `Nominal: Rp ${nominal}\n\n` +
                    `Apakah data sudah benar?`
                );
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.currency-input').forEach(i => formatCurrencyInput(i));

            // ✅ Validasi sebelum submit (double check)
            document.getElementById('formTambahSaldo').addEventListener('submit', function(e) {
                const jenis = document.getElementById('jenis_transaksi').value;
                const akun = document.getElementById('modal_akun_pengeluaran_id').value;
                const errorAkun = document.getElementById('errorAkun');

                if (jenis === 'pengeluaran' && !akun) {
                    e.preventDefault();
                    errorAkun.classList.remove('hidden');
                    document.getElementById('modal_akun_pengeluaran_id').focus();
                    return;
                }

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
                    '<svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
            });

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
                    '<svg class="animate-spin h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            });

            // Filter user
            const cabangSelect = document.getElementById('cabang_id'),
                userSelect = document.getElementById('user_id'),
                selectedUserId = "{{ request('user_id') }}";

            function fetchUsers(cabangId, currentUserId = null) {
                if (!cabangId) return;
                fetch(`/get-users-by-cabang/${cabangId}`).then(r => r.json()).then(data => {
                    userSelect.innerHTML = '<option value="">-- Pilih Akun --</option>';
                    data.forEach(u => {
                        userSelect.innerHTML +=
                            `<option value="${u.id}" ${u.id == currentUserId ? 'selected' : ''}>${u.name}</option>`;
                    });
                });
            }
            if (cabangSelect.value) fetchUsers(cabangSelect.value, selectedUserId);
            cabangSelect.addEventListener('change', function() {
                fetchUsers(this.value);
            });

            const modalUserSelect = document.getElementById('modal_user_id'),
                cabangHidden = document.getElementById('modal_cabang_id_hidden');
            modalUserSelect.addEventListener('change', function() {
                cabangHidden.value = this.options[this.selectedIndex].getAttribute('data-cabang-id') || '';
            });

            // Selisih
            const keluar = document.getElementById('nominal_keluar'),
                masuk = document.getElementById('nominal_masuk'),
                box = document.getElementById('selisihBox'),
                value = document.getElementById('selisihValue');

            function updateSelisih() {
                const k = parseCurrency(keluar.value),
                    m = parseCurrency(masuk.value);
                if (k > 0 && m > 0) {
                    const s = k - m;
                    box.classList.remove('hidden');
                    value.textContent = 'Rp ' + s.toLocaleString('id-ID');
                } else {
                    box.classList.add('hidden');
                }
            }
            keluar.addEventListener('input', function() {
                if (!masuk.value) masuk.value = this.value;
                updateSelisih();
            });
            masuk.addEventListener('input', updateSelisih);
        });
    </script>
@endsection
