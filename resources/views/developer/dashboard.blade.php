@extends('layouts.app')

@section('title', 'Developer Dashboard | Omzetly.id')

@section('container')
    <div class="relative w-full max-w-full overflow-x-hidden space-y-6 pb-12 mt-5">

        {{-- Ornamen Background Khas SaaS --}}
        <div
            class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-60 pointer-events-none z-0">
        </div>

        {{-- Header --}}
        <div
            class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span
                        class="px-2.5 py-1 bg-indigo-100 text-indigo-700 rounded-md text-[10px] font-bold uppercase tracking-wider border border-indigo-200">
                        Developer Mode
                    </span>
                    <span
                        class="flex items-center gap-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        System Online
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-800 tracking-tight mt-2">
                    Pusat Kendali <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-600">Omzetly.id</span>
                </h1>
                <p class="text-slate-500 mt-1 text-sm font-medium">Pantau performa bisnis, metrik SaaS, dan status agen
                    secara real-time.</p>
            </div>

            <div class="shrink-0 flex items-center gap-3">
                <a href="{{ route('developer.paket.index') }}"
                    class="px-4 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-bold rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                    Kelola Paket
                </a>
                <a href="{{ route('developer.pelanggan.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                    Kelola Pelanggan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>
        </div>

        {{-- Top KPI Cards (Metrik Utama) --}}
        <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            {{-- 1. Laba Kotor (Highlight Kontras Tinggi) --}}
            <div
                class="bg-slate-900 p-6 rounded-2xl border border-slate-800 shadow-xl shadow-slate-900/20 text-white relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.64-2.1 1.64-1.64 0-2.1-.96-2.17-1.92H8.21c.07 1.96 1.25 3.01 2.69 3.39V19h2.32v-1.64c1.77-.28 2.94-1.28 2.94-2.86 0-1.92-1.3-2.69-3.85-3.36z" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total Laba Kotor</p>
                    <p class="text-2xl lg:text-3xl font-black text-emerald-400 tracking-tight">Rp
                        {{ number_format($stats['omzet_global'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-3 font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Akumulasi selisih admin Bank
                    </p>
                </div>
            </div>

            {{-- 2. Volume Transaksi (DATA ASLI DARI DATABASE) --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Volume Transaksi</p>
                        <div class="p-1.5 bg-violet-50 text-violet-600 rounded-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl lg:text-3xl font-black text-slate-800 tracking-tight">
                        {{ number_format($stats['volume_transaksi'] ?? 0, 0, ',', '.') }}
                        <span class="text-sm font-bold text-slate-400">trx</span>
                    </p>
                </div>
                <p class="text-[11px] font-bold text-violet-600 mt-3 bg-violet-50 inline-block px-2 py-1 rounded w-max">
                    Bulan ini
                </p>
            </div>

            {{-- 3. Total Tenant --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Agen/Toko</p>
                        <div class="p-1.5 bg-blue-50 text-blue-600 rounded-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl lg:text-3xl font-black text-slate-800 tracking-tight">{{ $stats['total_agen'] ?? 0 }}
                    </p>
                </div>
                <div class="mt-4 flex items-center gap-1.5 flex-wrap">
                    <span
                        class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded border border-emerald-100 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> {{ $stats['active_agen'] ?? 0 }} Aktif
                    </span>
                    <span
                        class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded border border-amber-100">
                        ⌛ {{ $stats['trial_agen'] ?? 0 }} Trial
                    </span>
                    <span class="px-2 py-0.5 bg-rose-50 text-rose-700 text-[10px] font-bold rounded border border-rose-100">
                        ❌ {{ $stats['expired_agen'] ?? 0 }} Expired
                    </span>
                </div>
            </div>

            {{-- 4. Total Cabang --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Cabang</p>
                        <div class="p-1.5 bg-slate-100 text-slate-600 rounded-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-2xl lg:text-3xl font-black text-slate-800 tracking-tight">
                        {{ $stats['total_cabang'] ?? 0 }}</p>
                </div>
                <p class="text-[11px] font-semibold text-slate-500 mt-3 flex items-center gap-1">
                    Tersebar di berbagai lokasi
                </p>
            </div>
        </div>

        {{-- Mini Stats (Secondary KPIs) --}}
        <div class="relative z-10 grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Total User</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
                <div
                    class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                    👤</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Total Transaksi</p>
                    <p class="text-xl font-bold text-slate-800 mt-0.5">{{ $stats['total_transaksi'] ?? 0 }}</p>
                </div>
                <div
                    class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400">
                    🔄</div>
            </div>
            <div
                class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center justify-between border-l-4 border-l-blue-500">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Agen Baru Bulan Ini</p>
                    <p class="text-xl font-bold text-blue-600 mt-0.5">+{{ $stats['new_this_month'] ?? 0 }}</p>
                </div>
            </div>
            <div
                class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex items-center justify-between border-l-4 border-l-emerald-500">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Estimasi MRR</p>
                    <p class="text-xl font-bold text-emerald-600 mt-0.5" title="Berdasarkan total laba kotor">Rp
                        {{ number_format($stats['omzet_global'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Main Layout (Table & Sidebar) --}}
        <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kolom Kiri: Tabel Agen Terbaru --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Agen / Tenant Terbaru</h2>
                            <p class="text-xs text-slate-500 mt-0.5 font-medium">Daftar pengguna yang baru saja bergabung
                                ke platform.</p>
                        </div>
                        <a href="{{ route('developer.pelanggan.index') }}"
                            class="text-indigo-600 text-sm font-bold hover:text-indigo-800 transition-colors bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse whitespace-nowrap">
                            <thead class="bg-white border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama
                                        Toko</th>
                                    <th
                                        class="px-5 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider hidden sm:table-cell">
                                        Paket</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">Tgl
                                        Gabung</th>
                                    <th class="px-5 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-5 py-3.5 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($agen_terbaru as $agen)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs border border-indigo-100 shrink-0">
                                                    {{ substr($agen->nama_toko, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800">{{ $agen->nama_toko }}</p>
                                                    <p class="text-[11px] text-slate-400 font-medium font-mono mt-0.5">ID:
                                                        {{ $agen->id_tenant }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 hidden sm:table-cell font-medium">
                                            @if ($agen->plan && $agen->plan->harga == 0)
                                                <span class="text-slate-500">{{ $agen->plan->nama_paket ?? '-' }}</span>
                                            @else
                                                <span
                                                    class="text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md text-xs border border-indigo-100">{{ $agen->plan->nama_paket ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-slate-600 font-medium">
                                            {{ $agen->created_at ? $agen->created_at->format('d M Y') : '-' }}
                                        </td>
                                        <td class="px-5 py-4">
                                            @if ($agen->status_langganan == 'active')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">AKTIF</span>
                                            @elseif($agen->status_langganan == 'trial')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 uppercase tracking-wide">TRIAL</span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 uppercase tracking-wide">{{ $agen->status_langganan }}</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('developer.pelanggan.show', $agen->id_tenant) }}"
                                                class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-slate-300 text-slate-700 text-xs font-bold rounded shadow-sm hover:bg-slate-50 hover:text-indigo-600 transition-colors">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center text-slate-400">
                                                <svg class="w-10 h-10 mb-3 text-slate-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                <span class="text-sm font-medium text-slate-500">Belum ada data agen
                                                    baru.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: System Info & Quick Actions --}}
            <div class="space-y-6">

                {{-- Panel Konsol Sistem --}}
                <div
                    class="bg-slate-900 rounded-2xl shadow-lg border border-slate-800 p-6 text-slate-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16zM12 22.27L3.5 17.36V7.55L12 2.64l8.5 4.91v9.81L12 22.27z" />
                        </svg>
                    </div>

                    <h2 class="text-lg font-bold text-white mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                        Sistem & Lingkungan
                    </h2>

                    <div class="space-y-4">
                        <div class="flex justify-between items-end border-b border-slate-800 pb-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Aplikasi</p>
                            <p class="font-mono text-indigo-400 text-sm font-bold">Omzetly.id v1.0.4</p>
                        </div>
                        <div class="flex justify-between items-end border-b border-slate-800 pb-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Framework</p>
                            <p class="font-mono text-slate-200 text-sm">Laravel v{{ app()->version() }}</p>
                        </div>
                        <div class="flex justify-between items-end border-b border-slate-800 pb-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">PHP Version</p>
                            <p class="font-mono text-slate-200 text-sm">v{{ phpversion() }}</p>
                        </div>
                        <div class="flex justify-between items-end border-b border-slate-800 pb-2">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Database</p>
                            <div class="flex items-center gap-1.5">
                                <span
                                    class="h-2 w-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                                <p class="text-sm font-medium text-emerald-400">Connected</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-end pt-1">
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Waktu Server</p>
                            <p class="text-xs font-mono text-slate-400">{{ now()->format('d M Y - H:i') }} WIB</p>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions (Aksi Cepat) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('developer.pelanggan.index') }}"
                            class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors group">
                            <div
                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                                👥</div>
                            <span class="text-xs font-bold text-slate-700 text-center">Kelola<br>Pelanggan</span>
                        </a>
                        <a href="{{ route('developer.paket.index') }}"
                            class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-blue-300 hover:bg-blue-50 transition-colors group">
                            <div
                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-blue-600 mb-2 group-hover:scale-110 transition-transform">
                                📦</div>
                            <span class="text-xs font-bold text-slate-700 text-center">Manajemen<br>Paket</span>
                        </a>
                        {{-- Dummy Link Tambahan (Log Aktivitas) --}}
                        <a href="#"
                            class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 transition-colors group cursor-not-allowed opacity-70">
                            <div
                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-emerald-600 mb-2">
                                📊</div>
                            <span class="text-xs font-bold text-slate-700 text-center">Log<br>Aktivitas</span>
                        </a>
                        {{-- Dummy Link Tambahan (Pengaturan) --}}
                        <a href="#"
                            class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-xl border border-slate-200 hover:border-slate-400 hover:bg-slate-100 transition-colors group cursor-not-allowed opacity-70">
                            <div
                                class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-600 mb-2">
                                ⚙️</div>
                            <span class="text-xs font-bold text-slate-700 text-center">Pengaturan<br>Global</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
