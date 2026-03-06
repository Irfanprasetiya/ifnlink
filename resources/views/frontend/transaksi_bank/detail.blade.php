@extends('layouts.frontend.app')

@section('container')
    <!-- Data Transaksi Section -->
    <section class="bg-white rounded-lg shadow-md p-6 mb-10">
        <div class="grid grid-cols-2 max-md:grid-cols-1">
            <p class="text-start text-2xl font-semibold mb-2 select-none cursor-default">Transaksi Bank
                {{ $bank->nama_bank }}
            </p>
        </div>

        <!-- form input -->
        <div class="relative overflow-x-auto mt-2">
            <form action="{{ route('transaksi_banks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="bank_id" value="{{ $bank->id }}">

                @if (session('error'))
                    <div class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 10A8 8 0 11 2 10a8 8 0 0116 0z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif


                <div class="grid gap-6 mb-6 md:grid-cols-2">

                    <!-- Tanggal -->

                    <input type="hidden" name="waktu_transaksi" value="{{ now() }}"
                        class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5" required>


                    <!-- Jenis Transaksi -->
                    <div>
                        <label for="jenis_transaksi_id" class="block mb-2 text-sm font-medium text-gray-900">Jenis
                            Transaksi</label>
                        <select name="jenis_transaksi_id" id="jenis_transaksi_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5"
                            required>
                            <option value="" disabled selected>Pilih Jenis</option>
                            @foreach ($jenisTransaksis as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->nama_transaksi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- No Tujuan -->
                    <div>
                        <label for="no_tujuan" class="block mb-2 text-sm font-medium text-gray-900">No. Tujuan</label>
                        <input type="text" name="no_tujuan" id="no_tujuan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                    </div>

                    <!-- Nominal -->
                    <div>
                        <label for="nominal" class="block mb-2 text-sm font-medium text-gray-900">Nominal</label>
                        <input type="number" name="nominal" id="nominal"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5"
                            required>
                    </div>

                    <!-- Bayar -->
                    <div>
                        <label for="bayar" class="block mb-2 text-sm font-medium text-gray-900">Bayar</label>
                        <input type="number" name="bayar" id="bayar"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5"
                            required>
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900">Keterangan</label>
                        <input type="text" name="keterangan" id="keterangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5">
                    </div>
                </div>

                <button type="submit" id="btnSimpan"
                    class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5">Simpan</button>

            </form>

        </div>
        </div>

    </section>

    <script>
        document.getElementById('btnSimpan').addEventListener('click', function() {
            this.disabled = true;
            this.innerText = 'Menyimpan...';
            this.form.submit();
        });
    </script>
@endsection
