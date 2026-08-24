@extends('layouts.frontend.app')

@section('container')
    <main class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
        <!-- Header Box -->
        <section class="bg-white rounded-lg shadow-md p-5 mt-20 mb-8">
            <div class="flex justify-between items-center flex-wrap">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 rounded overflow-hidden flex-shrink-0">
                        <img src="{{ asset('assets/images/a6.jpg') }}"
                            alt="Logo merah kuning JC dengan tulisan pilihan kita bersama di bawahnya"
                            class="object-contain w-full h-full"
                            onerror="this.onerror=null;this.src='https:storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/1f5b150c-5e0d-4d47-be9f-199775963a42.png';" />
                    </div>
                    <h2 class="text-lg font-semibold select-none cursor-default">Cabang
                        {{ Auth::user()->cabang->nama_cabang }}
                    </h2>
                </div>
                <!-- <div class="text-right text-sm lg:text-gray-800 select-none cursor-default max-sm:pt-3">
                                                                                                                                                                                                                                                                                                                                                        Hallo {{ Auth::user()->name }}, Selamat Datang
                                                                                                                                                                                                                                                                                                                                                    </div> -->
            </div>
            <!-- breadcrumb -->
            <x-breadcrumb />

        </section>

        <!-- Data Transaksi Section -->
        {{-- <section class="bg-white rounded-lg shadow-md p-6 mb-10">
            <div class="grid grid-cols-2 max-md:grid-cols-1">
                <h1 class="text-start text-lg font-semibold mb-6 select-none cursor-default">Laporan Transaksi Konter</h1>

            </div>

            <div class="grid grid-cols-2 max-sm:grid-cols-1 py-auto">
                <div>
                    <form method="GET" action="{{ route('laporan_konter') }}" class="flex gap-3 items-end mb-4">
                        <div>
                            <label for="tanggal" class="block text-sm">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ $tanggal }}"
                                class="border rounded px-3 py-2">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-1">Tampilkan</button>
                        </div>
                    </form>
                </div>

                <div class="flex justify-center items-center max-sm:justify-start">
                    <h1 class="text-lg font-semibold select-none cursor-default">Petugas: {{Auth::user()->name}}
                    </h1>
                </div>
            </div>

            <div class="relative overflow-x-auto mt-3">
                <table class="min-w-full text-sm text-left text-gray-500">
                    <thead class="text-xs uppercase bg-[#1d62fb] text-white">
                        <tr>
                            <th class="px-4 py-3">No</th>
                            <th class="px-4 py-3">Waktu Transaksi</th>
                            <th class="px-4 py-3">Nama Produk</th>
                            <th class="px-4 py-3">Satuan Harga</th>
                            <th class="px-4 py-3">Qty</th>
                            <th class="px-4 py-3">Total Harga</th>
                            <th class="px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($penjualans as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2">{{ $item->created_at->format('Y-m-d H:i:s') }}</td>
                                <td class="px-4 py-2">
                                    {{ $item->produkKonter->voucher->nama_produk ?? 'Produk tidak ditemukan' }}
                                </td>
                                <td class="px-4 py-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $item->qty }}</td>
                                <td class="px-4 py-2">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="px-4 py-2">{{ $item->keterangan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-center text-gray-500">Tidak ada data penjualan</td>
                            </tr>
                        @endforelse
                    </tbody>

                    <tfoot class="bg-gray-100">
                        <tr>
                            <td colspan="5" class="px-4 py-2 font-semibold text-center">Total</td>
                            <td class="px-4 py-2 font-semibold text-left text-green-600">
                                Rp {{ number_format($penjualans->sum('total_harga'), 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>


                </table>
            </div>

        </section> --}}
        <section class="bg-white rounded-lg shadow-md p-6 mb-10 flex-grow">
            <h2 class="text-center text-lg font-semibold select-none cursor-default mb-4">
                Proses pengerjaan
            </h2>
            <div class="flex justify-center">
                <img src="{{ asset('assets/images/work.svg') }}" alt="Web Maintenance" class="w-64 h-auto mx-auto">
            </div>
            <p class="text-center text-gray-600 mt-4 text-sm">
                Tahapan pengerjaan sedang berlangsung, mohon menunggu hasil akhir.
            </p>
        </section>
    </main>
@endsection
