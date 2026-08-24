@extends('layouts.app')

@section('container')
    <div class="mt-7 grid grid-cols-3 mb-4 max-sm:grid-cols-1">
        <div>
            <h1 class="text-xl font-bold">Data Produk Konter</h1>
        </div>

        <div class="max-sm:mt-3">
            <form class="max-w-sm">
                <select id="countries"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Cari cabang</option>
                    @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="max-sm:mt-3">
            <button data-modal-target="create-modal" data-modal-toggle="create-modal"
                class="text-white float-right bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-sm max-sm:float-left">+
                Produk Konter</button>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- alert error -->
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg shadow">
            {{ session('error') }}
        </div>
    @endif


    <!-- Modal Tambah Produk Konter -->
    <form method="POST" action="{{ route('data_master.produk_konter.store') }}">
        @csrf
        <div id="create-modal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Tambah Produk Konter</h3>

                <div class="mb-3">
                    <label class="block text-sm">Voucher</label>
                    <select name="voucher_id" required
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                        @foreach ($vouchers as $voucher)
                            <option value="{{ $voucher->id }}">{{ $voucher->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Cabang</label>
                    <select name="cabang_id" required
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Stok</label>
                    <input type="number" name="stok" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Keterangan</label>
                    <input type="text" name="keterangan" class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" data-modal-toggle="create-modal"
                        class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Tabel Produk Konter -->
    <div class="mt-5 overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-800 dark:text-white">
            <thead class="text-xs uppercase bg-blue-700 text-white">
                <tr>
                    <th class="px-4 py-2">Nama Voucher</th>
                    <th class="px-4 py-2">Cabang</th>
                    <th class="px-4 py-2">Stok</th>
                    <th class="px-4 py-2">Keterangan</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produkKonters as $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $item->voucher->nama_produk }}</td>
                        <td class="px-4 py-2">{{ $item->cabang->nama_cabang }}</td>
                        <td class="px-4 py-2">{{ $item->stok }}</td>
                        <td class="px-4 py-2">{{ $item->keterangan }}</td>
                        <td class="px-4 py-2 flex gap-1">
                            <!-- Edit Button -->
                            <button data-modal-toggle="edit-modal-{{ $item->id }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 text-xs rounded">Edit</button>

                            <!-- Delete Form -->
                            <form method="POST" action="{{ route('data_master.produk_konter.destroy', $item->id) }}"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 text-xs rounded">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <form method="POST" action="{{ route('data_master.produk_konter.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div id="edit-modal-{{ $item->id }}"
                            class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                                <h3 class="text-lg font-bold mb-4">Edit Produk Konter</h3>

                                <div class="mb-3">
                                    <label class="block text-sm">Voucher</label>
                                    <select name="voucher_id"
                                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                                        @foreach ($vouchers as $voucher)
                                            <option value="{{ $voucher->id }}"
                                                {{ $voucher->id == $item->voucher_id ? 'selected' : '' }}>
                                                {{ $voucher->nama_produk }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm">Cabang</label>
                                    <select name="cabang_id"
                                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                                        @foreach ($cabangs as $cabang)
                                            <option value="{{ $cabang->id }}"
                                                {{ $cabang->id == $item->cabang_id ? 'selected' : '' }}>
                                                {{ $cabang->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm">Stok</label>
                                    <input type="number" name="stok" value="{{ $item->stok }}"
                                        class="w-full border rounded px-3 py-2">
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm">Keterangan</label>
                                    <input type="text" name="keterangan" value="{{ $item->keterangan }}"
                                        class="w-full border rounded px-3 py-2">
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                        class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data produk konter</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
