@extends('layouts.frontend.app')

@section('container')
    <!-- Data Laporan Section -->
    <section class="bg-white rounded-lg shadow-md p-6 mb-10 flex-grow">
        <div class="grid grid-cols-2 max-md:grid-cols-1">
            <h2 class="text-start text-lg font-semibold mb-6 select-none cursor-default">
                Laporan Transaksi Bank
            </h2>
        </div>

        <!-- Filter Tanggal -->
        <form method="GET" action="{{ route('laporan-bank') }}" class="flex flex-wrap gap-3 items-end mb-4">
            <div>
                <label for="tanggal" class="block text-sm">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal"
                    value="{{ request('tanggal', \Carbon\Carbon::now()->toDateString()) }}"
                    class="border rounded px-3 py-2">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-1">Tampilkan</button>
            </div>
        </form>

        <div class="relative overflow-x-auto mt-5">
            <table class="min-w-full text-sm text-left text-gray-700 bg-white rounded-lg shadow-md">
                <thead class="text-xs uppercase bg-gradient-to-r bg-[#2563eb] to-indigo-600 text-white">
                    <tr class="text-center">
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Waktu Transaksi</th>
                        <th class="px-4 py-3">Nama Transaksi</th>
                        <th class="px-4 py-3">Bank</th>
                        <th class="px-4 py-3 text-right">Nominal</th>
                        <th class="px-4 py-3 text-right">Bayar</th>
                        <th class="px-4 py-3 text-right">Saldo Akhir</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $no = 1; @endphp
                    @forelse ($transaksis as $trx)
                        @php
                            $isKas = strtolower($trx->bank->nama_bank ?? '') === 'kas';
                            $isTarikTunai = strtolower($trx->jenis_transaksi->nama_transaksi ?? '') === 'tarik tunai';
                        @endphp

                        {{-- Sembunyikan transaksi kas tarik tunai ke bank --}}
                        @if (!($isKas && $isTarikTunai))
                            <tr class="hover:bg-gray-50 even:bg-gray-100">
                                <td class="px-4 py-2">{{ $no++ }}</td>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('d-m-Y || H:i:s') }}
                                </td>
                                <td class="px-4 py-2 text-center">{{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}</td>
                                <td class="px-4 py-2 text-center">{{ $trx->bank->nama_bank ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-semibold text-blue-700">Rp
                                    {{ number_format($trx->nominal ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right text-green-700">Rp
                                    {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-indigo-700 saldo-akhir"
                                    data-bank="{{ strtolower($trx->bank->nama_bank ?? '-') }}"
                                    data-waktu="{{ $trx->waktu_transaksi }}">
                                    Rp {{ number_format($trx->saldo_akhir ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-2">{{ $trx->keterangan ?? '-' }}</td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">
                                Tidak ada transaksi pada tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a href="{{ route('laporan-bank.rekap', ['tanggal' => request('tanggal')]) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded mt-4 inline-block
                           {{ request()->routeIs('laporan-bank.rekap') ? 'text-blue-600' : '' }}">
                Rekap Laporan
            </a>
        </div>
    </section>
@endsection
