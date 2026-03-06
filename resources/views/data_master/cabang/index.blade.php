@extends('layouts.app')

@section('container')
    <!-- content -->
    <div class="mt-5 overflow-x-hidden">
        <div class="flex justify-between items-center">
            <div class="mt-5 font-bold text-xl lg:text-black sm:text-blue-500">
                <h1>Daftar Cabang</h1>
            </div>
            <div>
                <!-- Modal toggle -->
                <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                    class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    type="button">
                    Tambah Cabang
                </button>

                <!-- Main Modal: Register User -->
                <form method="POST" action="{{ route('data_master.cabang.store') }}">
                    @csrf
                    <div id="crud-modal" tabindex="-1" aria-hidden="true"
                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Tambah Cabang
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                        data-modal-toggle="crud-modal">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>

                                <!-- Modal body -->
                                <div class="p-4 md:p-5">
                                    <div class="grid gap-4 mb-4 grid-cols-2">
                                        <!-- Nama Cabang -->
                                        <div class="col-span-2">
                                            <label for="nama_cabang"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                                Cabang</label>
                                            <input type="text" name="nama_cabang" id="nama_cabang"
                                                value="{{ old('nama_cabang') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                placeholder="Nama Cabang" required>
                                            <x-input-error :messages="$errors->get('nama_cabang')" class="mt-2" />
                                        </div>

                                        <!-- Alamat Cabang -->
                                        <div class="col-span-2">
                                            <label for="alamat_cabang"
                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat
                                                cabang</label>
                                            <input type="text" name="alamat_cabang" id="alamat_cabang"
                                                value="{{ old('alamat_cabang') }}"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                placeholder="Alamat Cabang" required>
                                            <x-input-error :messages="$errors->get('alamat_cabang')" class="mt-2" />
                                        </div>
                                    </div>

                                    <!-- keterangan -->
                                    <div class="col-span-2">
                                        <label for="keterangan"
                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                                        <input type="text" name="keterangan" id="keterangan"
                                            value="{{ old('keterangan', $cabang->keterangan ?? '') }}"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                            placeholder="Keterangan">
                                        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                        class="w-full mt-5 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        {{ __('Tambah Cabang') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>



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



        <!-- table -->
        <div class="relative overflow-x-auto mt-6">
            <table class="min-w-full text-sm text-left text-gray-700 bg-white rounded-lg shadow-md">
                <thead class="text-xs uppercase bg-gradient-to-r bg-[#2563eb] to-indigo-600 text-white">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-center">
                            No
                        </th>
                        <th scope="col" class="px-4 py-3">
                            Nama Cabang
                        </th>
                        <th scope="col" class="px-4 py-3">
                            Alamat
                        </th>
                        <th scope="col" class="px-4 py-3">
                            Keterangan
                        </th>
                        <th scope="col" class="px-4 py-3 text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @php $no = 1; @endphp
                    @forelse ($cabangs as $cabang)
                        <tr class="hover:bg-gray-50 even:bg-gray-100">
                            <td class="px-4 py-3 text-center font-medium">{{ $no++ }}</td>
                            <td class="px-4 py-3">{{ $cabang->nama_cabang }}</td>
                            <td class="px-4 py-3">{{ $cabang->alamat_cabang }}</td>
                            <td class="px-4 py-3">{{ $cabang->keterangan }}</td>
                            <td class="px-4 py-3 flex justify-center gap-2">
                                <!-- Edit -->
                                <button type="button" data-modal-target="edit-modal-{{ $cabang->id }}"
                                    data-modal-toggle="edit-modal-{{ $cabang->id }}"
                                    class="p-2 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit">
                                    <!-- Icon Pencil -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232
                                                                                                   a2 2 0 112.828 2.828L11.828 13.828
                                                                                                   a2 2 0 01-1.414.586H9v-2z" />
                                    </svg>
                                </button>

                                <!-- Hapus -->
                                <form action="{{ route('cabang.destroy', $cabang->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus cabang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2 rounded-full bg-red-100 hover:bg-red-200 text-red-600">
                                        <!-- Icon Trash -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862
                                                                                               a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                                                               M9 7h6m2 0a2 2 0 012 2v0a2 2 0 01-2 2H7
                                                                                               a2 2 0 01-2-2v0a2 2 0 012-2h10z" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div id="edit-modal-{{ $cabang->id }}"
                            class="hidden fixed inset-0 z-50 justify-center items-center bg-black bg-opacity-50">
                            <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-md w-full max-w-md">
                                <div class="flex justify-between mb-4">
                                    <h3 class="text-lg font-bold">Edit Cabang</h3>
                                    <button type="button" data-modal-toggle="edit-modal-{{ $cabang->id }}"
                                        class="text-gray-400 hover:text-gray-600">✕</button>
                                </div>
                                <form method="POST" action="{{ route('cabang.update', $cabang->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium">Nama Cabang</label>
                                        <input type="text" name="nama_cabang" value="{{ $cabang->nama_cabang }}"
                                            required
                                            class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium">Alamat Cabang</label>
                                        <input type="text" name="alamat_cabang" value="{{ $cabang->alamat_cabang }}"
                                            required
                                            class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div class="mb-3">
                                        <label for="keterangan" class="block text-sm font-medium">Keterangan</label>
                                        <input type="text" name="keterangan" value="{{ $cabang->keterangan }}"
                                            required
                                            class="w-full border rounded px-3 py-2 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Simpan</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-500">Belum ada data cabang</td>
                        </tr>
                    @endforelse

        </div>


    </div>
@endsection
