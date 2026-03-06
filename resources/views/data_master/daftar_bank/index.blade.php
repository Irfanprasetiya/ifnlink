@extends('layouts.app')
@section('container')
    <div class="mt-5">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Daftar Data Bank</h1>
            <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                Tambah Bank
            </button>
        </div>

        <!-- Alert -->
        @if (session('success'))
            <div id="alert-success"
                class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100 dark:bg-gray-800 dark:text-green-400"
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

        <!-- Table -->
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-white uppercase bg-blue-700 dark:bg-blue-600">
                    <tr>
                        <th class="px-6 py-3 rounded-s-lg">No</th>
                        <th class="px-6 py-3">Nama Bank</th>
                        <th class="px-6 py-3">Tanggal Dibuat</th>
                        <th class="px-6 py-3 rounded-e-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banks as $i => $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">{{ $i + 1 }}</td>
                            <td class="px-6 py-4">{{ $item->nama_bank }}</td>
                            <td class="px-6 py-4">{{ $item->created_at_format }}</td>
                            <td class="px-6 py-4 flex gap-2">
                                <button data-modal-target="edit-modal-{{ $item->id }}"
                                    data-modal-toggle="edit-modal-{{ $item->id }}"
                                    class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-xs">Edit</button>
                                <form action="{{ route('data_master.daftar_bank.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf @method('DELETE')
                                    <button
                                        class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-xs">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div id="edit-modal-{{ $item->id }}"
                            class="hidden fixed inset-0 z-50 flex items-center justify-center">
                            <div class="relative w-full max-w-md p-4">
                                <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                                    <div class="flex justify-between items-center p-4 border-b">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Bank</h3>
                                        <button type="button" class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5"
                                            data-modal-toggle="edit-modal-{{ $item->id }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                    <form action="{{ route('data_master.daftar_bank.update', $item->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="p-4 space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-white">Nama
                                                    Bank</label>
                                                <input type="text" name="nama_bank" required
                                                    value="{{ $item->nama_bank }}"
                                                    class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-600 dark:text-white">
                                            </div>
                                        </div>
                                        <div class="p-4 border-t text-end">
                                            <button type="submit"
                                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div id="tambah-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div class="relative w-full max-w-md p-4">
            <div class="bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tambah Bank</h3>
                    <button type="button" class="text-gray-400 hover:bg-gray-200 rounded-lg p-1.5"
                        data-modal-toggle="tambah-modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('data_master.daftar_bank.store') }}" method="POST">
                    @csrf
                    <div class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-white">Nama Bank</label>
                            <input type="text" name="nama_bank" required
                                class="w-full mt-1 p-2 border rounded-lg dark:bg-gray-600 dark:text-white">
                        </div>
                    </div>
                    <div class="p-4 border-t text-end">
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
