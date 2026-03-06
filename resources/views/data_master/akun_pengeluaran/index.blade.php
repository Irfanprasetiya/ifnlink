@extends('layouts.app')
@section('container')
    <x-admin.breadcrumb />

    <div class="mt-5">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold">Daftar Akun Pengeluaran</h1>
            <button data-modal-target="tambah-modal" data-modal-toggle="tambah-modal"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                Tambah Akun
            </button>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-center font-semibold text-gray-800">
                <thead class="text-xs text-white uppercase bg-blue-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Nama Akun</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse ($data as $item)
                        <tr class="bg-white border-b">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">{{ $item->datetime }}</td>
                            <td class="px-6 py-4">{{ $item->nama_akun }}</td>
                            <td class="px-6 py-4">{{ $item->keterangan }}</td>
                            <td class="px-6 py-4 flex justify-center gap-2">

                                <button data-modal-target="edit-modal-{{ $item->id }}"
                                    data-modal-toggle="edit-modal-{{ $item->id }}"
                                    class="text-white bg-yellow-500 px-3 py-1 rounded text-xs">
                                    Edit
                                </button>

                                <form action="{{ route('data_master.akun_pengeluaran.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-white bg-red-600 px-3 py-1 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit -->
                        <div id="edit-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
                            class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">

                            <div class="relative w-full max-w-md h-full md:h-auto">

                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                                    <!-- Modal header -->
                                    <div
                                        class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                            Edit Akun Pengeluaran
                                        </h3>
                                        <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-hide="edit-modal-{{ $item->id }}">
                                            ✕
                                        </button>
                                    </div>

                                    <!-- Form -->
                                    <form action="{{ route('data_master.akun_pengeluaran.update', $item->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Modal body -->
                                        <div class="p-4 md:p-5">

                                            <div class="grid gap-4 mb-4 grid-cols-2">

                                                <!-- Tanggal -->
                                                <div class="col-span-2">
                                                    <label for="datetime"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                        Tanggal
                                                    </label>
                                                    <input type="datetime-local" name="datetime" id="datetime"
                                                        value="{{ date('Y-m-d\TH:i', strtotime($item->datetime)) }}"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                        required>

                                                    <x-input-error :messages="$errors->get('datetime')" class="mt-2" />
                                                </div>

                                                <!-- Nama Akun -->
                                                <div class="col-span-2">
                                                    <label for="nama_akun"
                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                        Nama Akun
                                                    </label>
                                                    <input type="text" name="nama_akun" id="nama_akun"
                                                        value="{{ $item->nama_akun }}"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                        required>

                                                    <x-input-error :messages="$errors->get('nama_akun')" class="mt-2" />
                                                </div>

                                            </div>

                                            <!-- Keterangan -->
                                            <div class="col-span-2">
                                                <label for="keterangan"
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                    Keterangan
                                                </label>
                                                <input type="text" name="keterangan" id="keterangan"
                                                    value="{{ $item->keterangan }}"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5
                            dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                                    placeholder="Opsional">

                                                <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                                            </div>

                                            <!-- Submit -->
                                            <button type="submit"
                                                class="w-full mt-5 text-white bg-blue-700 hover:bg-blue-800
                        focus:ring-4 focus:outline-none focus:ring-blue-300
                        font-medium rounded-lg text-sm px-5 py-2.5 text-center
                        dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                Update Akun
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-gray-500">
                                ⚠ Belum ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Modal Tambah -->
    <div id="tambah-modal" tabindex="-1" aria-hidden="true"
        class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">

        <div class="relative w-full max-w-md h-full md:h-auto">

            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Tambah Akun Pengeluaran
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="tambah-modal">
                        ✕
                    </button>
                </div>

                <!-- Form -->
                <form id="form-akun" action="{{ route('data_master.akun_pengeluaran.store') }}" method="POST">
                    @csrf

                    <!-- Modal body -->
                    <div class="p-4 md:p-5">

                        <div class="grid gap-4 mb-4 grid-cols-2">

                            <!-- Tanggal -->
                            <input type="datetime-local" name="datetime" id="datetime"
                                value="{{ old('datetime', now()->format('Y-m-d\TH:i')) }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                required>


                            <!-- Nama Akun -->
                            <div class="col-span-2">
                                <label class="block mb-2 text-sm font-medium">
                                    Nama Akun
                                </label>

                                <input type="text" name="nama_akun" id="nama_akun_tambah"placeholder="Nama Akun"
                                    class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>

                                <p id="alert-akun" class="hidden text-sm text-red-600 mt-2">

                                    ⚠ Nama akun ini sudah ada
                                </p>
                            </div>

                        </div>

                        <!-- Keterangan -->
                        <div class="col-span-2">
                            <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Keterangan
                            </label>
                            <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5
                                dark:bg-gray-600 dark:border-gray-500 dark:text-white"
                                placeholder="Opsional">

                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full mt-5 text-white bg-blue-700 hover:bg-blue-800
                            focus:ring-4 focus:outline-none focus:ring-blue-300
                            font-medium rounded-lg text-sm px-5 py-2.5 text-center
                            dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Simpan Akun
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('nama_akun_tambah');
        const alertBox = document.getElementById('alert-akun');
        const form = document.getElementById('form-akun');

        let statusAda = false;

        // CEK NAMA
        input.addEventListener('keyup', function() {

            let nama = this.value.trim();

            if (nama.length < 2) {
                alertBox.classList.add('hidden');
                statusAda = false;
                return;
            }

            fetch(`/data_master/akun-pengeluaran/check/${nama}`)
                .then(res => res.json())
                .then(res => {

                    if (res.exists) {
                        alertBox.classList.remove('hidden');
                        statusAda = true;
                    } else {
                        alertBox.classList.add('hidden');
                        statusAda = false;
                    }
                });
        });

        // TAHAN SUBMIT
        form.addEventListener('submit', function(e) {

            if (statusAda) {
                e.preventDefault(); // STOP SUBMIT
                alertBox.classList.remove('hidden');
            }
        });
    </script>
@endsection
