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
        <section class="bg-white rounded-lg shadow-md p-6 mb-10">
            <div class="grid grid-cols-2 max-md:grid-cols-1">
                <h2 class="text-start text-lg font-semibold mb-6 select-none cursor-default">Transaksi:
                    {{ $produk->voucher->nama_produk }}

                </h2>
            </div>

            <!-- alert error -->
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow">
                    {{ session('error') }}
                </div>
            @endif

            <!-- form input -->
            <div class="relative overflow-x-auto mt-5">
                <form method="POST" action="{{ route('penjualan.store') }}">
                    @csrf
                    <input type="hidden" name="produk_konter_id" value="{{ $produk->id }}">

                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="qty"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Qty</label>
                            <input type="number" id="qty" name="qty"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Jumlah Pembelian" required />
                        </div>
                        <div>
                            <label for="harga"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga</label>
                            <input type="text" id="harga" name="harga"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="{{ $produk->voucher->harga_jual }}" required readonly />
                        </div>
                        <div>
                            <label for="harga_grosir"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Harga Grosir (Bisa
                                Disesuaikan)</label>
                            <input type="number" id="harga_grosir" name="harga_grosir"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="{{ $produk->voucher->harga_jual }}" required />
                        </div>
                        <div>
                            <label for="total_harga"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Harga</label>
                            <input type="number" id="total_harga" name="total_harga"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required />
                        </div>
                        <div>
                            <div class="flex gap-2">
                                <label for="keterangan"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                                <span class="text-sm text-red-600 font-bold">Opsional</span>
                            </div>
                            <input type="text" id="keterangan" name="keterangan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Keterangan" />
                        </div>
                    </div>


                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Simpan</button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const qty = document.getElementById('qty');
                        const harga = document.getElementById('harga');
                        const total = document.getElementById('total_harga');

                        function hitungTotal() {
                            const totalHarga = (parseInt(qty.value || 0) * parseInt(harga.value || 0));
                            total.value = totalHarga;
                        }

                        qty.addEventListener('input', hitungTotal);
                        harga.addEventListener('input', hitungTotal);
                    });
                </script>



            </div>
            </div>

        </section>
    </main>
@endsection
