@extends('layouts.frontend.app')
@section('title', 'Laporan Setoran Harian')
@section('container')
    <div class="w-full mx-auto mt-20 px-4 py-6 max-w-6xl">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Setoran Harian</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $user->name ?? '-' }} · {{ $user->cabang->nama_cabang ?? '-' }} ·
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('laporan-setoran.pdf', ['tanggal' => $tanggal, 'user_id' => $user->id ?? '']) }}"
                    class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M4 4v16h16V8l-4-4H4z" />
                        <path d="M14 4v4h4" />
                    </svg>
                    Unduh PDF
                </a>
                <a href="{{ route('laporan-setoran.excel', ['tanggal' => $tanggal, 'user_id' => $user->id ?? '']) }}"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg px-4 py-2.5 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v3h16v-3" />
                    </svg>
                    Unduh Excel
                </a>
            </div>
        </div>

        {{-- Ringkasan Kas (metric cards) --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-6">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs text-gray-500 mb-1">Saldo Awal Kas</p>
                <p class="text-lg font-semibold text-blue-600">Rp {{ number_format($saldoAwalKas, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs text-gray-500 mb-1">Tambahan Kas</p>
                <p class="text-lg font-semibold text-amber-600">Rp {{ number_format($tambahanKas, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs text-gray-500 mb-1">Pengurangan Kas</p>
                <p class="text-lg font-semibold text-red-600">Rp {{ number_format($penguranganKas, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs text-gray-500 mb-1">Total Transfer</p>
                <p class="text-lg font-semibold text-amber-600">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <p class="text-xs text-gray-500 mb-1">Total Tarik Tunai</p>
                <p class="text-lg font-semibold text-amber-600">Rp {{ number_format($totalTarikTunai, 0, ',', '.') }}</p>
            </div>
            <div class="bg-blue-50 rounded-xl border border-blue-100 shadow-sm p-4">
                <p class="text-xs text-blue-600 mb-1">Saldo Akhir Kas</p>
                <p class="text-lg font-bold text-blue-700">Rp {{ number_format($saldoAkhirKas, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Saldo Bank --}}
        <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-6 mb-6">
            <h2 class="text-base font-semibold text-gray-700 mb-4">Saldo Bank</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach ($saldoBank as $bank => $saldo)
                    @if (strtolower($bank) !== 'kas')
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $bank }}</p>
                            <p class="text-base font-semibold text-blue-600 mt-1">
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Grafik --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-6">
                <h2 class="text-base font-semibold text-gray-700 mb-4">Grafik Transaksi</h2>
                <canvas id="grafikTransaksi" height="200"></canvas>
            </div>

            <div class="bg-white shadow-sm border border-gray-100 rounded-xl p-6">
                <h2 class="text-base font-semibold text-gray-700 mb-4">Distribusi Saldo Bank</h2>
                <canvas id="grafikPieBank" height="200"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxBar = document.getElementById('grafikTransaksi').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Transfer', 'Tarik Tunai'],
                datasets: [{
                    label: 'Jumlah (Rp)',
                    data: [{{ $totalTransfer }}, {{ $totalTarikTunai }}],
                    backgroundColor: ['#F59E0B', '#2563EB']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const ctxPie = document.getElementById('grafikPieBank').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: {!! json_encode(collect($saldoBank)->keys()->reject(fn($b) => strtolower($b) === 'kas')->values()) !!},
                datasets: [{
                    data: {!! json_encode(collect($saldoBank)->reject(fn($saldo, $b) => strtolower($b) === 'kas')->values()) !!},
                    backgroundColor: ['#2563EB', '#F59E0B', '#6B7280', '#93C5FD', '#FDE68A']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>
@endsection
