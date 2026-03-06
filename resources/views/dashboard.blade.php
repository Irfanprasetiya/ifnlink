@extends('layouts.app')

@section('title', 'Dashboard')

@section('container')
    {{-- Filter --}}
    <form method="GET" class="flex flex-col md:flex-row gap-4 mb-6 mt-5">
        <select name="periode" class="border rounded px-3 py-2 w-full md:w-auto">
            <option value="harian" {{ $periode == 'harian' ? 'selected' : '' }}>Harian</option>
            <option value="mingguan" {{ $periode == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
            <option value="bulanan" {{ $periode == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
        </select>

        <select name="cabang_id" class="border rounded px-3 py-2 w-full md:w-auto">
            <option value="semua" {{ $cabangId == 'semua' ? 'selected' : '' }}>Semua Cabang</option>
            @foreach ($cabangs as $cabang)
                <option value="{{ $cabang->id }}" {{ $cabangId == $cabang->id ? 'selected' : '' }}>
                    {{ $cabang->nama_cabang }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full md:w-auto">
            Terapkan
        </button>
    </form>

    {{-- Ringkasan Omzet --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-600 flex justify-between align-center p-4 rounded-lg shadow-lg">
            <div>
                <h3 class="text-sm text-white">Omzet (Laba Kotor)</h3>
                <p class=" text-2xl font-bold text-white">Rp {{ number_format($totalLabaKotor) }}</p>
            </div>
            <div>
                <svg class="w-12 h-12 text-yellow-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5" />
                </svg>
            </div>

        </div>
        <div class="bg-red-600 flex justify-between align-center p-4 rounded-lg shadow-lg">
            <div>
                <h3 class="text-sm text-white">Profit</h3>
                <p class=" text-2xl font-bold text-white">Rp {{ number_format($totalLabaKotor) }}</p>
            </div>
            <div>
                <svg class="w-12 h-12 text-yellow-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4.5V19a1 1 0 0 0 1 1h15M7 14l4-4 4 4 5-5m0 0h-3.207M20 9v3.207" />
                </svg>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Grafik Omzet --}}
        <div class="bg-white p-6 rounded shadow-lg mb-6">
            <h2 class="text-lg font-semibold mb-4">Omzet</h2>
            <div class="w-full overflow-x-auto">
                <canvas id="chartOmzet" class="w-full h-64 sm:h-72 md:h-80 lg:h-96"></canvas>
            </div>
        </div>

        {{-- Perbandingan Cabang --}}
        <div class="bg-white p-6 rounded shadow-lg mb-6">
            <h2 class="text-lg font-semibold mb-4">Perbandingan Cabang</h2>
            <div class="w-full overflow-x-auto">
                <canvas id="chartCabang" class="w-full h-64 sm:h-72 md:h-80 lg:h-96"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('chartOmzet'), {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsOmzet) !!},
                datasets: [{
                    label: 'Omzet (Laba Kotor)',
                    data: {!! json_encode($dataOmzet) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('chartCabang'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelsCabang) !!},
                datasets: [{
                    label: 'Total Omzet per Cabang',
                    data: {!! json_encode($dataCabang) !!},
                    backgroundColor: '#6366f1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
