@extends('layouts.app')

@section('container')
    <div class="container mx-auto px-4 mt-6">
        <h1 class="mt-3 mb-5 text-xl font-bold">Data Barang Masuk</h1>

        <div class="flex justify-between items-center mb-4">


            <form method="GET" action="{{ route('barang_masuk.index') }}" class="flex flex-wrap gap-3 items-end mb-4">
                <div>
                    <label for="cabang_id" class="block text-sm">Cabang</label>
                    <select name="cabang_id" id="cabang_id" class="border rounded px-3 py-2">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                {{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tanggal" class="block text-sm">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}"
                        class="border rounded px-3 py-2">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-1">Filter</button>
                    <a href="{{ route('barang_masuk.index') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded mt-1">Reset</a>
                </div>
            </form>


            <button data-modal-toggle="create-modal" data-modal-target="create-modal"
                class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-sm">
                + Barang Masuk
            </button>

        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="text-xs uppercase bg-blue-700 text-white">
                <tr>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Voucher</th>
                    <th class="px-4 py-2">Cabang</th>
                    <th class="px-4 py-2">Qty</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangMasuks as $item)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $item->tanggal }}</td>
                        <td class="px-4 py-2">{{ $item->produk_konter->voucher->nama_produk ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->produk_konter->cabang->nama_cabang ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->qty }}</td>
                        <td class="px-4 py-2 flex gap-1">
                            <button data-modal-toggle="edit-modal-{{ $item->id }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 text-xs rounded">Edit</button>

                            <form action="{{ route('barang_masuk.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 text-xs rounded">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <form method="POST" action="{{ route('barang_masuk.update', $item->id) }}">
                        @csrf @method('PUT')
                        <div id="edit-modal-{{ $item->id }}"
                            class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                                <h3 class="text-lg font-bold mb-4">Edit Barang Masuk</h3>

                                <div class="mb-3">
                                    <label class="block text-sm">Produk Konter</label>
                                    <select name="produk_konter_id" required
                                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                                        @foreach ($produkKonters as $pk)
                                            <option value="{{ $pk->id }}" {{ $item->produk_konter_id == $pk->id ? 'selected' : '' }}>
                                                {{ $pk->voucher->nama_produk }} - {{ $pk->cabang->nama_cabang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ $item->tanggal }}"
                                        class="w-full border rounded px-3 py-2" required>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-sm">Qty</label>
                                    <input type="number" name="qty" value="{{ $item->qty }}"
                                        class="w-full border rounded px-3 py-2" required>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                        class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada data barang masuk</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>

    <!-- Modal Tambah Barang Masuk -->
    <form method="POST" action="{{ route('barang_masuk.store') }}">
        @csrf
        <div id="create-modal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Tambah Barang Masuk</h3>

                <div class="mb-3">
                    <label class="block text-sm">Produk Konter</label>
                    <select name="produk_konter_id" required
                        class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                        @foreach ($produkKonters as $pk)
                            <option value="{{ $pk->id }}">
                                {{ $pk->voucher->nama_produk }} - {{ $pk->cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Tanggal</label>
                    <input type="date" name="tanggal" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm">Qty</label>
                    <input type="number" name="qty" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" data-modal-toggle="create-modal"
                        class="bg-gray-300 px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </div>
        </div>
    </form>
@endsection