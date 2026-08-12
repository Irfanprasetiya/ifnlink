@extends('layouts.app')

@section('title', 'Status Langganan')

@section('container')
    <div class="px-4 sm:px-6 lg:px-8 py-8 max-w-6xl mx-auto min-h-screen">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Langganan Saya</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm sm:text-base">Pantau status paket, kuota penggunaan, dan
                kelola tagihan Anda.</p>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div
                class="flex items-center gap-3 mb-6 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-400 px-4 py-4 rounded-r-xl shadow-sm">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="flex items-center gap-3 mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 px-4 py-4 rounded-r-xl shadow-sm">
                <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Tagihan Pending --}}
        @if ($hasPendingPayment)
            @php
                $pendingPembayaran = $pembayarans->where('status', 'pending')->first();
                $pendingPlan = $pendingPembayaran
                    ? \App\Models\Plan::find(session('upgrade_plan_id')) ?? $tenant->plan
                    : $tenant->plan;
                $pendingHarga = $pendingPembayaran ? $pendingPembayaran->jumlah : $pendingPlan->harga;
                $pendingNama = $pendingPlan ? $pendingPlan->nama_paket : $tenant->plan->nama_paket;
            @endphp

            <div
                class="mb-6 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-400 px-5 py-4 rounded-xl">
                <div class="flex items-start gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-amber-200 dark:bg-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-amber-700 dark:text-amber-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-amber-800 dark:text-amber-300">Masih ada tagihan belum dibayar</h3>
                        <p class="text-sm mt-1">
                            Paket <strong>{{ $pendingNama }}</strong> —
                            <strong>Rp {{ number_format($pendingHarga, 0, ',', '.') }}</strong>.
                            Selesaikan atau batalkan dulu sebelum beli paket baru.
                        </p>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <a href="{{ route('checkout', $pendingPlan->id ?? $tenant->plan_id) }}"
                                class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">💳
                                Bayar Sekarang</a>
                            <form action="{{ route('status.batalkan') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 bg-white border border-red-300 text-red-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-50 transition"
                                    onclick="return confirm('Yakin ingin membatalkan tagihan ini?')">❌ Batalkan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- TOP SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 mb-10">

            <!-- KIRI: Card -->
            <div
                class="lg:col-span-7 xl:col-span-8 relative overflow-hidden rounded-3xl bg-white dark:bg-gray-800 p-6 sm:p-8 shadow-xl border border-gray-200 dark:border-gray-700">
                <div
                    class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-blue-50 dark:bg-blue-900/20 blur-3xl pointer-events-none">
                </div>
                <div
                    class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-indigo-50 dark:bg-indigo-900/20 blur-3xl pointer-events-none">
                </div>

                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <p class="text-blue-600 dark:text-blue-400 text-sm font-bold uppercase tracking-widest mb-1">
                                Paket Saat Ini</p>
                            <h2
                                class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white flex items-center gap-3">
                                {{ $tenant->plan->nama_paket ?? '-' }}
                                @if ($tenant->plan && $tenant->plan->harga == 0)
                                    <span
                                        class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-1.5 rounded-full border font-bold">GRATIS</span>
                                @endif
                            </h2>
                        </div>
                        <div>
                            @if ($tenant->status_langganan == 'active')
                                <span
                                    class="flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-400 px-3 py-1.5 rounded-full text-sm font-bold shadow-sm"><span
                                        class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Aktif</span>
                            @elseif($tenant->status_langganan == 'trial')
                                <span
                                    class="flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 dark:bg-amber-900/30 dark:border-amber-800 dark:text-amber-400 px-3 py-1.5 rounded-full text-sm font-bold shadow-sm"><span
                                        class="w-2 h-2 rounded-full bg-amber-500"></span> Trial</span>
                            @elseif($tenant->status_langganan == 'pending')
                                <span
                                    class="flex items-center gap-2 bg-gray-100 border border-gray-200 text-gray-700 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 px-3 py-1.5 rounded-full text-sm font-bold shadow-sm"><span
                                        class="w-2 h-2 rounded-full bg-gray-500"></span> Menunggu Bayar</span>
                            @else
                                <span
                                    class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 dark:bg-red-900/30 dark:border-red-800 dark:text-red-400 px-3 py-1.5 rounded-full text-sm font-bold shadow-sm"><span
                                        class="w-2 h-2 rounded-full bg-red-500"></span>
                                    {{ ucfirst($tenant->status_langganan) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t border-gray-100 dark:border-gray-700 pt-6">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm mb-1">Berlangganan Sejak</p>
                            <p class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">
                                {{ $tenant->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 dark:text-gray-400 text-xs sm:text-sm mb-1">Berlaku Sampai</p>
                            <p class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">
                                @if ($tenant->tanggal_berakhir)
                                    {{ \Carbon\Carbon::parse($tenant->tanggal_berakhir)->format('d M Y') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($tenant->plan && $tenant->plan->harga > 0 && $tenant->tanggal_berakhir)
                        @php
                            $start = $tenant->created_at;
                            $end = \Carbon\Carbon::parse($tenant->tanggal_berakhir);
                            $totalDays = $start->diffInDays($end);
                            $daysLeft = now()->diffInDays($end, false);
                            $percent =
                                $totalDays > 0
                                    ? max(0, min(100, (($totalDays - max(0, $daysLeft)) / $totalDays) * 100))
                                    : 0;
                        @endphp
                        <div class="mt-8 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-2xl border">
                            <div class="flex justify-between text-xs sm:text-sm mb-2">
                                <span>Masa Aktif</span>
                                <span class="{{ $daysLeft <= 7 ? 'text-red-600 font-bold' : 'text-blue-600 font-bold' }}">
                                    @if ($daysLeft > 0)
                                        Sisa {{ floor($daysLeft) }} hari
                                    @else
                                        Berakhir
                                    @endif
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                                <div class="h-full rounded-full {{ $daysLeft <= 7 ? 'bg-red-500' : 'bg-blue-600' }}"
                                    style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- KANAN -->
            <div class="lg:col-span-5 xl:col-span-4 flex flex-col justify-between space-y-6">
                @if ($tenant->plan && $tenant->plan->harga == 0)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border shadow-sm">
                        <h3 class="font-bold mb-4">⚡ Limit Paket Gratis</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between"><span class="text-sm text-gray-500">Transaksi/Hari</span><span
                                    class="font-bold">10</span></div>
                            <div class="flex justify-between"><span class="text-sm text-gray-500">Cabang</span><span
                                    class="font-bold">1</span></div>
                            <div class="flex justify-between"><span class="text-sm text-gray-500">User</span><span
                                    class="font-bold">{{ $tenant->users->count() }}/{{ $tenant->max_user ?? 3 }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-3xl p-6 shadow-sm">
                        <h3 class="font-extrabold text-amber-900 dark:text-amber-400 text-lg mb-1">Buka Semua Fitur! 🚀</h3>
                        <p class="text-sm text-amber-700 dark:text-amber-300 mb-4">Nikmati unlimited transaksi & multi-user.
                        </p>
                        <a href="{{ route('upgrade') }}"
                            class="block w-full text-center bg-gradient-to-r from-amber-500 to-orange-500 text-red-600 font-bold py-3.5 rounded-xl shadow-lg hover:from-amber-600 hover:to-orange-600 transition">Upgrade
                            ke PRO</a>
                    </div>
                @elseif($tenant->plan && $tenant->plan->harga > 0)
                    @php $daysLeft = $tenant->tanggal_berakhir ? max(0, (int) now()->diffInDays(\Carbon\Carbon::parse($tenant->tanggal_berakhir), false)) : 0; @endphp
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl p-6 border shadow-sm flex-1 flex flex-col justify-center">
                        @if ($daysLeft <= 3)
                            <div class="text-center mb-5">
                                <div
                                    class="inline-flex items-center justify-center w-14 h-14 rounded-full {{ $daysLeft == 0 ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600' }} mb-3">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-lg">
                                    {{ $daysLeft == 0 ? 'Langganan Berakhir' : 'Segera Berakhir' }}
                                </h3>
                            </div>

                            @php
                                $isPending = Auth::user()->tenant && Auth::user()->tenant->isLocked();
                            @endphp

                            <a href="{{ $isPending ? route('status.perpanjang') : route('status.perpanjang') }}"
                                class="block w-full text-center {{ $daysLeft == 0 ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold py-3.5 rounded-xl transition">
                                {{ $isPending ? 'Bayar Sekarang' : 'Perpanjang Langganan' }}
                            </a>
                        @else
                            <div class="text-center">
                                <div
                                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-xl mb-2">Semua Berjalan Lancar</h3>
                                <p class="text-sm text-gray-500">Sisa <strong>{{ $daysLeft }} hari</strong>.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Riwayat Pembayaran --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl border shadow-sm overflow-hidden">
            <div class="p-6 border-b bg-gray-50/50 dark:bg-gray-800/50">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Riwayat Tagihan & Pembayaran</h2>
            </div>

            @if ($pembayarans->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead
                            class="bg-gray-50 dark:bg-gray-900/20 text-gray-600 dark:text-gray-400 text-xs uppercase font-bold">
                            <tr>
                                <th class="py-4 px-6">No. Tagihan</th>
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Metode</th>
                                <th class="py-4 px-6">Jumlah</th>
                                <th class="py-4 px-6 text-center">Status</th>
                                <th class="py-4 px-6 text-right">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            @foreach ($pembayarans as $p)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                    <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                        #INV-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 px-6 text-gray-600 dark:text-gray-400">
                                        {{ $p->created_at->format('d M Y H:i') }}</td>
                                    <td class="py-4 px-6">
                                        @if ($p->metode == 'bank_transfer')
                                            🏦 Transfer Bank
                                        @elseif($p->metode == 'gopay')
                                            📱 GoPay
                                        @else
                                            💳 {{ ucfirst(str_replace('_', ' ', $p->metode)) }}
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-extrabold text-gray-900 dark:text-white">Rp
                                        {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($p->status == 'confirmed')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">✅
                                                Lunas</span>
                                        @elseif($p->status == 'pending')
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">⏳
                                                Menunggu</span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">❌
                                                {{ ucfirst($p->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('status.invoice', $p->id) }}"
                                            class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-gray-100 text-gray-600 hover:bg-blue-100 hover:text-blue-700 transition"
                                            title="Download PDF">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-16 text-center">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-900 dark:text-white font-bold text-lg">Belum ada riwayat tagihan</p>
                    <p class="text-sm text-gray-500 mt-1">Transaksi langganan Anda akan muncul di sini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
