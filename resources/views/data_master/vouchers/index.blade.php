@extends('layouts.app')
@section('container')
    <div class="mt-5">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-bold">Data Voucher</h1>

            <!-- Button Tambah -->
            <button data-modal-target="voucher-modal" data-modal-toggle="voucher-modal"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300">
                Tambah Voucher
            </button>
        </div>

        <!-- Modal Tambah Voucher -->
        <form method="POST" action="{{ route('data_master.vouchers.store') }}">
            @csrf
            <div id="voucher-modal" tabindex="-1" aria-hidden="true"
                class="hidden fixed inset-0 z-50 items-center justify-center overflow-y-auto overflow-x-hidden">
                <div class="relative w-full max-w-md p-4">
                    <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                        <div class="flex justify-between items-center p-4 border-b dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Voucher</h3>
                            <button type="button" class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5"
                                data-modal-toggle="voucher-modal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24">
                                    <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <div class="p-4 space-y-4">
                            <div>
                                <label for="nama_produk"
                                    class="block text-sm font-medium text-gray-700 dark:text-white">Nama Produk</label>
                                <input type="text" name="nama_produk" id="nama_produk" required
                                    class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="harga_beli"
                                    class="block text-sm font-medium text-gray-700 dark:text-white">Harga Beli</label>
                                <input type="number" step="0.01" name="harga_beli" id="harga_beli" required
                                    class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="harga_jual"
                                    class="block text-sm font-medium text-gray-700 dark:text-white">Harga Jual</label>
                                <input type="number" step="0.01" name="harga_jual" id="harga_jual" required
                                    class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-600 dark:text-white">
                            </div>
                            <div>
                                <label for="kategori_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-white">Kategori</label>
                                <select name="kategori_id" id="kategori_id" required
                                    class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-600 dark:text-white">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="keterangan"
                                    class="block text-sm font-medium text-gray-700 dark:text-white">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3"
                                    class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-600 dark:text-white"></textarea>
                            </div>
                        </div>
                        <div class="p-4 border-t dark:border-gray-600 text-end">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if (session('success'))
            <div id="alert-success"
                class="flex items-center p-4 mt-4 mb-4 text-green-800 rounded-lg bg-green-100 dark:bg-gray-800 dark:text-green-400"
                role="alert">
                <svg class="flex-shrink-0 w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8.707-2.707a1 1 0 1 0-1.414 1.414L9.586 10l-1.707 1.707a1 1 0 1 0 1.414 1.414L11 11.414l1.707 1.707a1 1 0 0 0 1.414-1.414L12.414 10l1.707-1.707a1 1 0 0 0-1.414-1.414L11 8.586 9.293 6.879Z" />
                </svg>
                <div class="ms-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
                <button type="button"
                    class="ms-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700"
                    data-dismiss-target="#alert-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1l6 6m0 0l6 6M7 7L1 13m6-6l6-6" />
                    </svg>
                </button>
            </div>
        @endif


        <!-- Tabel -->
        <div class="relative overflow-x-auto mt-6">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-white uppercase bg-blue-700 dark:bg-blue-600">
                    <tr>
                        <th class="px-6 py-3 rounded-s-lg">Nama Produk</th>
                        <th class="px-6 py-3">Harga Beli</th>
                        <th class="px-6 py-3">Harga Jual</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3 rounded-e-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $voucher->nama_produk }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($voucher->harga_beli, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($voucher->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">{{ $voucher->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $voucher->keterangan }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <!-- Edit -->
                                <button type="button" data-modal-target="edit-modal-{{ $voucher->id }}"
                                    data-modal-toggle="edit-modal-{{ $voucher->id }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                    Edit
                                </button>

                                <!-- Hapus -->
                                <form action="{{ route('data_master.vouchers.destroy', $voucher->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit Voucher -->
                        <div id="edit-modal-{{ $voucher->id }}" tabindex="-1"
                            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-lg">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Voucher</h3>
                                    <button type="button" data-modal-toggle="edit-modal-{{ $voucher->id }}"
                                        class="text-gray-400 hover:text-red-600 text-xl">×</button>
                                </div>

                                <form method="POST" action="{{ route('data_master.vouchers.update', $voucher->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Nama
                                            Produk</label>
                                        <input type="text" name="nama_produk" value="{{ $voucher->nama_produk }}"
                                            required
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Harga
                                            Beli</label>
                                        <input type="number" name="harga_beli" value="{{ $voucher->harga_beli }}"
                                            required
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-white">Harga
                                            Jual</label>
                                        <input type="number" name="harga_jual" value="{{ $voucher->harga_jual }}"
                                            required
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-white">Kategori</label>
                                        <select name="kategori_id"
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ $voucher->kategori_id == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-4">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-white">Keterangan</label>
                                        <input type="text" name="keterangan" value="{{ $voucher->keterangan }}"
                                            class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
