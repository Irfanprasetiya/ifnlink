@extends('layouts.app')

@section('container')
    <div class="container mx-auto px-4 mt-6">

        <h1 class="mt-3 mb-5 text-xl font-bold">Data Laporan Saldo</h1>

        <form method="GET" action="{{ route('laporan_saldo.index') }}" class="mb-4 flex items-center gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}"
                class="border rounded px-3 py-2 text-sm focus:ring focus:ring-blue-300">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                Filter
            </button>
        </form>
    </div>

    <!-- Table -->
    <div class="relative overflow-x-auto mt-5">
        <table class="w-full text-sm text-left text-gray-800">
            <thead class="text-xs text-white uppercase bg-blue-700">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Cabang</th>
                    @foreach ($banks as $bank)
                        <th class="px-6 py-3 text-center">{{ strtoupper($bank->nama_bank) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($cabangs as $index => $cabang)
                    <tr class="bg-white even:bg-gray-100 font-semibold">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ $cabang->nama_cabang }}</td>
                        @foreach ($banks as $bank)
                            <td class="px-6 py-4 text-center">
                                {{ number_format($saldo[$cabang->id][$bank->id] ?? 0, 0, ',', '.') }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 2 + $banks->count() }}" class="text-center py-6 text-gray-500 font-semibold">
                            ❌ Data tidak ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-200 font-semibold">
                <tr>
                    <td colspan="2" class="px-6 py-3 text-center text-xl">Total</td>
                    @foreach ($banks as $bank)
                        <td class="px-6 py-3 text-center">
                            {{ number_format($totalSaldo[$bank->id] ?? 0, 0, ',', '.') }}
                        </td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
