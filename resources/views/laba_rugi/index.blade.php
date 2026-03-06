@extends('layouts.app')

@section('container')
    <div class="container mx-auto px-4 py-6 space-y-6">

        <!-- Judul -->
        <div class="text-left">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-700">Laporan Laba Rugi Per Cabang</h1>
        </div>

        <!-- Filter -->
        <div>
            <form method="GET" class="flex flex-col md:flex-row md:space-x-4 space-y-3 md:space-y-0">
                <input type="date" name="tanggal_awal" value="{{ $tanggalAwal }}"
                    class="border rounded px-3 py-2 w-full md:w-auto">
                <input type="date" name="tanggal_akhir" value="{{ $tanggalAkhir }}"
                    class="border rounded px-3 py-2 w-full md:w-auto">

                <select name="cabang_id" class="border rounded px-3 py-2 w-full md:w-auto">
                    <option value="">-- Semua Cabang --</option>
                    @foreach ($allCabangs as $cabang)
                        <option value="{{ $cabang->id }}" {{ $cabang_id == $cabang->id ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full md:w-auto">
                    Filter
                </button>
            </form>
        </div>

        <!-- Ringkasan Global -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-sm text-gray-600">Total Laba Kotor</h2>
                <p class="text-xl md:text-2xl font-bold text-green-700">
                    Rp {{ number_format($totalLabaKotor ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-red-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-sm text-gray-600">Total Pengeluaran</h2>
                <p class="text-xl md:text-2xl font-bold text-red-700">
                    Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg shadow text-center">
                <h2 class="text-sm text-gray-600">Total Laba Bersih</h2>
                <p class="text-xl md:text-2xl font-bold text-blue-700">
                    Rp {{ number_format($totalLabaBersih ?? 0, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <!-- Card Per Cabang -->
        <!-- Card Per Cabang -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($cabangs as $cabang)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">{{ $cabang->nama_cabang }}</h3>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-500">Laba Kotor</span>
                        <span class="text-green-600 font-semibold">
                            Rp {{ number_format($labaKotor[$cabang->id] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-gray-500">Pengeluaran</span>
                        <span class="text-red-600 font-semibold">
                            Rp {{ number_format($pengeluaran[$cabang->id] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Laba Bersih</span>
                        <span class="text-blue-600 font-bold">
                            Rp {{ number_format($labaBersih[$cabang->id] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="mt-4 text-center">
                        @if (($labaBersih[$cabang->id] ?? 0) >= 0)
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Profit
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                Rugi
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
