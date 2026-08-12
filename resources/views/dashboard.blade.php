@extends('layouts.app')

@section('title', 'Dashboard')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12">

        {{-- Top Bar Header & Filter Modern --}}
        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard Overview</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ringkasan performa bisnis dan transaksi agen Anda secara
                    real-time.</p>
            </div>

            {{-- Filter Form --}}
            <form method="GET" class="flex flex-wrap items-center gap-2.5">
                <select name="periode"
                    class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
                    <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                    <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                </select>
                <select name="cabang_id"
                    class="bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    <option value="semua" {{ $cabangId == 'semua' ? 'selected' : '' }}>Semua Cabang</option>
                    @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->id }}" {{ $cabangId == $cabang->id ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}</option>
                    @endforeach
                </select>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold shadow-md shadow-blue-500/20 transition-all">Terapkan</button>
            </form>
        </div>

        {{-- Quick Actions + Jam Realtime --}}
        <div
            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-slate-900 text-white p-4 rounded-2xl shadow-lg">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('trx-bank.index') }}"
                    class="inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-500 text-white px-3.5 py-2 rounded-xl text-xs font-semibold transition shadow-sm"><span>➕</span>
                    Transaksi</a>
                <a href="{{ route('laporan-bank.admin.index') }}"
                    class="inline-flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 px-3.5 py-2 rounded-xl text-xs font-semibold transition border border-slate-700"><span>📄</span>
                    Laporan</a>
                <a href="{{ route('users.index') }}"
                    class="inline-flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 px-3.5 py-2 rounded-xl text-xs font-semibold transition border border-slate-700"><span>👥</span>
                    User</a>
                <a href="{{ route('data_master.cabang.index') }}"
                    class="inline-flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 px-3.5 py-2 rounded-xl text-xs font-semibold transition border border-slate-700"><span>🏢</span>
                    Cabang</a>
            </div>
            <div
                class="text-xs font-medium text-slate-400 bg-slate-800/80 px-3 py-1.5 rounded-xl border border-slate-700/60 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>🕒 <span id="jam" class="text-slate-200 font-semibold"></span></span>
            </div>
        </div>

        {{-- ✅ 2 Card Utama: Profit + Saldo Kas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Profit (Omzet - Pengeluaran) --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Profit</span>
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
                @php
                    $profit = ($totalLabaKotor ?? 0) - ($totalPengeluaran ?? 0);
                @endphp
                <div
                    class="text-xl sm:text-2xl font-extrabold {{ $profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} truncate">
                    {{ $profit >= 0 ? '' : '-' }}Rp {{ number_format(abs($profit), 0, ',', '.') }}
                </div>
                <p class="text-xs text-slate-400 mt-2 font-medium">Omzet - Biaya Operasional</p>
            </div>

            {{-- Saldo Kas --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 hover:shadow-md transition-all">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Saldo Kas</span>
                    <div
                        class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4.5V19a1 1 0 0 0 1 1h15M7 14l4-4 4 4-5-5" />
                        </svg>
                    </div>
                </div>
                <div class="text-xl sm:text-2xl font-extrabold text-purple-600 truncate">
                    Rp {{ number_format($totalSaldoKas ?? 0, 0, ',', '.') }}
                </div>
                <p class="text-xs text-slate-400 mt-2 font-medium">Akumulasi kas saat ini</p>
            </div>
        </div>

        {{-- Card Sekunder (Metrik Ringkas) --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Transaksi</p>
                <p class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">{{ number_format($totalTransaksi ?? 0) }}
                </p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Transfer</p>
                <p class="text-lg sm:text-xl font-extrabold text-blue-600 mt-1">{{ number_format($totalTransfer ?? 0) }}
                </p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tarik Tunai</p>
                <p class="text-lg sm:text-xl font-extrabold text-orange-600 mt-1">
                    {{ number_format($totalTarikTunai ?? 0) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Numpang TF</p>
                <p class="text-lg sm:text-xl font-extrabold text-indigo-600 mt-1">{{ number_format($totalNumpang ?? 0) }}
                </p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-4 text-center shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Cabang Aktif</p>
                <p class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1">{{ $cabangs->count() }}</p>
            </div>
        </div>

        {{-- Grafik Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">Grafik Profit</h2>
                    <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">Real-time</span>
                </div>
                <div class="w-full h-64 sm:h-72"><canvas id="chartOmzet"></canvas></div>
            </div>
            <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">Perbandingan Cabang</h2>
                    <span class="text-xs font-medium text-slate-400 bg-slate-100 px-2.5 py-1 rounded-lg">Kinerja Toko</span>
                </div>
                <div class="w-full h-64 sm:h-72"><canvas id="chartCabang"></canvas></div>
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Transaksi Terbaru</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Daftar transaksi mutakhir dari seluruh cabang.</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="px-6 py-3.5">Waktu</th>
                            <th class="px-6 py-3.5">Jenis</th>
                            <th class="px-6 py-3.5">Bank</th>
                            <th class="px-6 py-3.5 text-right">Nominal</th>
                            <th class="px-6 py-3.5 text-right">Bayar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($transaksiTerbaru as $trx)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-600">
                                    {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('H:i:s') }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">
                                    {{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}</td>
                                <td class="px-6 py-4"><span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">{{ $trx->bank->nama_bank ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900">Rp
                                    {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600">Rp
                                    {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-10 text-slate-400">Belum ada catatan transaksi
                                    saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Script --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function updateJam() {
                const now = new Date();
                document.getElementById('jam').textContent = now.toLocaleString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }
            setInterval(updateJam, 1000);
            updateJam();

            new Chart(document.getElementById('chartOmzet'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($labelsOmzet) !!},
                    datasets: [{
                        label: 'Profit',
                        data: {!! json_encode($dataOmzet) !!},
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5,150,105,0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#059669',
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9'
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('chartCabang'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($labelsCabang) !!},
                    datasets: [{
                        label: 'Total Omzet per Cabang',
                        data: {!! json_encode($dataCabang) !!},
                        backgroundColor: '#4f46e5',
                        borderRadius: 8,
                        barThickness: 'flex',
                        maxBarThickness: 36
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9'
                            }
                        }
                    }
                }
            });
        </script>
    </div>
@endsection
