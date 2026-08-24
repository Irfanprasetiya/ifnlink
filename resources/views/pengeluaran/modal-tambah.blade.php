{{-- MODAL TAMBAH --}}
<div id="crud-modal" tabindex="-1" aria-hidden="true"
    class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">

    <div class="relative w-full max-w-md h-full md:h-auto">

        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

            <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Tambah Pengeluaran
                </h3>

                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    data-modal-hide="tambah-modal">
                    ✕
                </button>
            </div>

            <form action="{{ route('pengeluaran.store') }}" method="POST">
                @csrf

                <div class="p-4 md:p-5">

                    <div class="grid gap-4 mb-4 grid-cols-2">

                        <!-- Tanggal -->
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium">
                                Tanggal
                            </label>

                            <input type="date" name="tanggal"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5"
                                value="{{ old('tanggal', now()->format('Y-m-d')) }}" required>
                        </div>

                        <!-- Cabang -->
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium">
                                Cabang
                            </label>

                            <select name="cabang_id" id="cabang_id" for="cabang_id"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                                <option value="">-- Pilih Cabang --</option>
                                @foreach ($cabangs as $row)
                                    <option value="{{ $row->id }}"
                                        {{ request('cabang_id') == $row->id ? 'selected' : '' }}>
                                        {{ $row->nama_cabang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- end -->

                        <!-- Akun -->
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium">
                                Akun Pengeluaran
                            </label>

                            <select name="akun_pengeluaran_id"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                                @foreach ($akun as $a)
                                    <option value="{{ $a->id }}">{{ $a->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nominal -->
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium">
                                Nominal
                            </label>

                            <input type="number" name="nominal"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                        </div>

                    </div>

                    <!-- Keterangan -->
                    <div class="col-span-2">
                        <label class="block mb-2 text-sm font-medium">
                            Keterangan
                        </label>

                        <input type="text" name="keterangan"
                            class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" placeholder="Opsional">
                    </div>

                    <button type="submit"
                        class="w-full mt-5 text-white bg-blue-700 hover:bg-blue-800
focus:ring-4 focus:outline-none focus:ring-blue-300
font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Simpan
                    </button>

                </div>
            </form>
        </div>
    </div>
</div>



{{-- MODAL EDIT --}}
@if (isset($item))
    <div id="edit-modal-{{ $item->id }}" tabindex="-1" aria-hidden="true"
        class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">

        <div class="relative w-full max-w-md h-full md:h-auto">

            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Edit Pengeluaran
                    </h3>

                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                        data-modal-hide="edit-modal-{{ $item->id }}">
                        ✕
                    </button>
                </div>

                <form action="{{ route('pengeluaran.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-4 md:p-5">

                        <div class="grid gap-4 mb-4 grid-cols-2">
                            <input type="hidden" name="tanggal_filter" value="{{ request('tanggal') }}">

                            <input type="hidden" name="cabang_filter" value="{{ request('cabang_id') }}">


                            <!-- Tanggal -->
                            <div class="col-span-2">
                                <label class="block mb-2 text-sm font-medium">
                                    Tanggal
                                </label>

                                <input type="date" name="tanggal" value="{{ $item->tanggal }}"
                                    class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                            </div>

                            <!-- Cabang -->
                            <div class="col-span-2">
                                <label class="block mb-2 text-sm font-medium">
                                    Cabang
                                </label>

                                <select name="cabang_id"
                                    class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                                    @foreach ($cabangs as $a)
                                        <option value="{{ $a->id }}"
                                            {{ $item->cabang_id == $a->id ? 'selected' : '' }}>
                                            {{ $a->nama_cabang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <!-- end -->

                            <!-- Akun -->
                            <div class="col-span-2">
                                <label class="block mb-2 text-sm font-medium">
                                    Akun Pengeluaran
                                </label>

                                <select name="akun_pengeluaran_id"
                                    class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                                    @foreach ($akun as $a)
                                        <option value="{{ $a->id }}"
                                            {{ $item->akun_pengeluaran_id == $a->id ? 'selected' : '' }}>
                                            {{ $a->nama_akun }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Nominal -->
                            <div class="col-span-2">
                                <label class="block mb-2 text-sm font-medium">
                                    Nominal
                                </label>

                                <input type="number" name="nominal" value="{{ $item->nominal }}"
                                    class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5" required>
                            </div>

                        </div>

                        <!-- Keterangan -->
                        <div class="col-span-2">
                            <label class="block mb-2 text-sm font-medium">
                                Keterangan
                            </label>

                            <input type="text" name="keterangan" value="{{ $item->keterangan }}"
                                class="bg-gray-50 border border-gray-300 rounded-lg w-full p-2.5">
                        </div>

                        <button type="submit"
                            class="w-full mt-5 text-white bg-blue-700 hover:bg-blue-800
focus:ring-4 focus:outline-none focus:ring-blue-300
font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Update
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
