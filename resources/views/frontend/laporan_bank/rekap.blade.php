@extends('layouts.frontend.app')
@section('title', 'Laporan Setoran Harian')
@section('container')
    <div class="w-full mx-auto mt-20 px-4 py-6">
        {{-- Header --}}
        <h1 class="text-2xl font-bold text-blue-600 mb-6">Laporan Setoran Harian</h1>

        {{-- Card Info Petugas --}}
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Petugas</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Cabang</p>
                    <p class="text-lg font-semibold text-gray-800">{{ $user->cabang->nama_cabang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Tanggal</p>
                    <p class="text-lg font-semibold text-gray-800">{{ \Carbon\Carbon::parse($tanggal)->format('d-m-Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Transaksi BriLink --}}
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Transaksi BriLink</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <p><span class="text-gray-500">Saldo Awal Kas:</span> <span class="font-semibold text-blue-600">Rp
                            {{ number_format($saldoAwalKas, 0, ',', '.') }}</span></p>
                    <p><span class="text-gray-500">Tambahan Kas:</span> <span class="font-semibold text-yellow-600">Rp
                            {{ number_format($tambahanKas, 0, ',', '.') }}</span></p>
                    <p><span class="text-gray-500">Pengurangan Kas:</span> <span class="font-semibold text-red-600">Rp
                            {{ number_format($penguranganKas, 0, ',', '.') }}</span></p>
                </div>
                <div class="space-y-2">
                    <p><span class="text-gray-500">Total Transfer:</span> <span class="font-semibold text-yellow-600">Rp
                            {{ number_format($totalTransfer, 0, ',', '.') }}</span></p>
                    <p><span class="text-gray-500">Total Tarik Tunai:</span> <span class="font-semibold text-yellow-600">Rp
                            {{ number_format($totalTarikTunai, 0, ',', '.') }}</span></p>
                    <p class="text-blue-600 font-bold">Saldo Akhir Kas: Rp {{ number_format($saldoAkhirKas, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Saldo Bank --}}
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-700 mb-4">Saldo Bank</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($saldoBank as $bank => $saldo)
                    @if (strtolower($bank) !== 'kas')
                        <div class="bg-gray-50 rounded-md p-4 border">
                            <p class="text-sm text-gray-500">{{ strtoupper($bank) }}</p>
                            <p class="text-lg font-semibold text-blue-600">
                                Rp {{ number_format($saldo, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Grafik --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Grafik Transfer vs Tarik Tunai --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Grafik Transaksi</h2>
                <canvas id="grafikTransaksi" height="200"></canvas>
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
                                backgroundColor: ['#F59E0B', '#2563EB'] // kuning & biru
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
                </script>
            </div>

            {{-- Grafik Pie Saldo Bank --}}
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-700 mb-4">Distribusi Saldo Bank</h2>
                <canvas id="grafikPieBank" height="200"></canvas>
                <script>
                    const ctxPie = document.getElementById('grafikPieBank').getContext('2d');
                    new Chart(ctxPie, {
                        type: 'pie',
                        data: {
                            labels: {!! json_encode(collect($saldoBank)->keys()->reject(fn($b) => strtolower($b) === 'kas')->values()) !!},
                            datasets: [{
                                data: {!! json_encode(collect($saldoBank)->reject(fn($saldo, $b) => strtolower($b) === 'kas')->values()) !!},
                                backgroundColor: ['#2563EB', '#F59E0B', '#6B7280', '#93C5FD',
                                    '#FDE68A'
                                ] // biru, kuning, abu
                            }]
                        },
                        options: {
                            responsive: true
                        }
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
