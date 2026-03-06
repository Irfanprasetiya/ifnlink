@extends('layouts.app')

@section('container')
    <!-- Header + Tambah -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold">Data Kategori</h1>
        <button data-modal-target="tambah-kategori-modal" data-modal-toggle="tambah-kategori-modal"
            class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
            Tambah Kategori
        </button>
    </div>

    <!-- Modal Tambah -->
    <form action="{{ route('data_master.kategoris.store') }}" method="POST">
        @csrf
        <div id="tambah-kategori-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg w-full max-w-md">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white">Tambah Kategori</h2>
                    <button type="button" data-modal-toggle="tambah-kategori-modal"
                        class="text-gray-500 hover:text-red-600">✕</button>
                </div>
                <div class="mb-4">
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 dark:text-white">Nama
                        Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori" required
                        class="w-full mt-1 p-2 border rounded dark:bg-gray-600 dark:text-white">
                </div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
            </div>
        </div>
    </form>

    <!-- Tabel Kategori -->
    <div class="relative overflow-x-auto mt-6">
        <table class="w-full text-sm text-left text-gray-800 dark:text-gray-200">
            <thead class="text-xs text-white uppercase bg-blue-700">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Kategori</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoris as $kategori)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-600">
                        <td class="px-6 py-4">{{ $kategori->nama_kategori }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <!-- Edit Button -->
                            <button type="button" data-modal-target="edit-modal-{{ $kategori->id }}"
                                data-modal-toggle="edit-modal-{{ $kategori->id }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">Edit</button>

                            <!-- Hapus -->
                            <form action="{{ route('data_master.kategoris.destroy', $kategori->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div id="edit-modal-{{ $kategori->id }}"
                        class="hidden fixed inset-0 z-50  items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg w-full max-w-md">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-bold">Edit Kategori</h2>
                                <button type="button" data-modal-toggle="edit-modal-{{ $kategori->id }}"
                                    class="text-gray-500 hover:text-red-600">✕</button>
                            </div>
                            <form action="{{ route('data_master.kategoris.update', $kategori->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label for="nama_kategori"
                                        class="block text-sm font-medium text-gray-700 dark:text-white">Nama
                                        Kategori</label>
                                    <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}"
                                        required class="w-full mt-1 p-2 border rounded dark:bg-gray-700 dark:text-white">
                                </div>
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada data
                            kategori
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
