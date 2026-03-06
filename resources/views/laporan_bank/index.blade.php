@extends('layouts.app')

@section('title', 'Laporan Bank')

@section('container')
    <div class="container mx-auto px-4 mt-6">
        <h1 class="mt-3 mb-5 text-xl font-bold">Riwayat Transaksi</h1>

        <div class="flex justify-between items-center mb-4">


            <form method="GET" action="{{ route('laporan-bank.admin.index') }}" class="flex flex-wrap gap-3 items-end mb-4">
                <div>
                    <label for="tanggal" class="block text-sm">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        value="{{ request('tanggal', \Carbon\Carbon::now()->toDateString()) }}"
                        class="border rounded px-3 py-2">
                </div>

                <div>
                    <label for="cabang_id" class="block text-sm">Cabang</label>
                    <select name="cabang_id" id="cabang_id" class="border rounded px-3 py-2">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}" {{ $cabang_id == $cabang->id ? 'selected' : '' }}>
                                {{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="user_id" class="block text-sm">Akun</label>
                    <select name="user_id" id="user_id" class="border rounded px-3 py-2">
                        <option value="">Semua Akun</option>
                        {{-- diisi lewat JS --}}
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-1">Cari</button>
                </div>
            </form>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif



            <!-- <button data-modal-toggle="create-modal" data-modal-target="create-modal"
                                                                                                                                                                                                                                                                                                                class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded text-sm">
                                                                                                                                                                                                                                                                                                                + Barang Masuk
                                                                                                                                                                                                                                                                                                            </button> -->

        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto mt-6">
        <table class="min-w-full text-sm text-left text-gray-700 bg-white rounded-lg shadow-md">
            <thead class="text-xs uppercase bg-gradient-to-r bg-[#2563eb] to-indigo-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3">Waktu Transaksi</th>
                    <th class="px-4 py-3">Nama Transaksi</th>
                    <th class="px-4 py-3">Bank</th>
                    <th class="px-4 py-3 text-right">Nominal</th>
                    <th class="px-4 py-3 text-right">Bayar</th>
                    <th class="px-4 py-3 text-right">Saldo Akhir</th>
                    <th class="px-4 py-3">Keterangan</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($transaksis as $index => $trx)
                    @php
                        $bankName = strtolower($trx->bank->nama_bank ?? '');
                        $jenis = strtolower($trx->jenis_transaksi->nama_transaksi ?? '');
                        $ket = strtolower($trx->keterangan ?? '');

                        $hiddenKasTypes = ['transfer', 'tarik tunai', 'numpang transfer'];
                    @endphp

                    {{-- SEMBUNYIKAN kas hasil transfer, TAPI BUKAN kas awal --}}
                    @if ($bankName === 'kas' && in_array($jenis, $hiddenKasTypes) && $ket !== 'kas awal')
                        @continue
                    @endif
                    <tr class="hover:bg-gray-50 even:bg-gray-100">
                        <!-- No -->
                        <td class="px-4 py-3 text-center font-medium">{{ $index + 1 }}</td>

                        <!-- Waktu -->
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($trx->waktu_transaksi)->format('d-m-Y || H:i:s') }}
                        </td>

                        <!-- Nama Transaksi -->
                        <td class="px-4 py-3">{{ $trx->jenis_transaksi->nama_transaksi ?? '-' }}</td>

                        <!-- Bank -->
                        <td class="px-4 py-3">{{ $trx->bank->nama_bank ?? '-' }}</td>

                        <!-- Nominal -->
                        <td class="px-4 py-3 text-right font-semibold text-blue-700">
                            Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                        </td>

                        <!-- Bayar -->
                        <td class="px-4 py-3 text-right text-green-700">
                            Rp {{ number_format($trx->bayar ?? 0, 0, ',', '.') }}
                        </td>

                        <!-- Saldo Akhir -->
                        <td class="px-4 py-3 text-right font-bold text-indigo-700">
                            Rp {{ number_format($trx->saldo_akhir_dynamic ?? 0, 0, ',', '.') }}
                        </td>

                        <!-- Keterangan -->
                        <td class="px-4 py-3">{{ $trx->keterangan ?? '-' }}</td>

                        <!-- Aksi -->
                        <td class="px-4 py-3 flex gap-2 justify-center">
                            <!-- Edit -->
                            <button type="button" data-modal-target="edit-modal-{{ $trx->id }}"
                                data-modal-toggle="edit-modal-{{ $trx->id }}"
                                class="p-2 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-600" title="Edit">
                                <!-- Icon Pencil -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536M9 11l6.232-6.232
                                                                                                   a2 2 0 112.828 2.828L11.828 13.828
                                                                                                   a2 2 0 01-1.414.586H9v-2z" />
                                </svg>
                            </button>

                            <!-- Hapus -->
                            <form method="POST" action="{{ route('laporan-bank.destroy', $trx->id) }}"
                                onsubmit="return confirm('Yakin ingin menghapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-full bg-red-100 hover:bg-red-200 text-red-600"
                                    title="Hapus">
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


                    {{-- Modal Edit --}}
                    <div id="edit-modal-{{ $trx->id }}" tabindex="-1" aria-hidden="true"
                        class="hidden fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-modal md:h-full">

                        <div class="relative w-full max-w-full md:max-w-md h-full md:h-auto">


                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">

                                <!-- Header -->
                                <div class="flex items-start justify-between p-4 border-b rounded-t">
                                    <h3 class="text-xl font-semibold text-gray-900">
                                        Edit Transaksi Bank
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex"
                                        data-modal-hide="edit-modal-{{ $trx->id }}">
                                        ✕
                                    </button>
                                </div>

                                <!-- Form -->
                                <form action="{{ route('laporan-bank.update', $trx->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="bank_id" value="{{ $trx->bank_id }}">

                                    <!-- Body -->
                                    <div class="p-4 md:p-5">

                                        <div class="grid gap-4 mb-4 grid-cols-1 md:grid-cols-2">

                                            <!-- Tanggal -->
                                            <div class="col-span-2">
                                                <label class="block mb-2 text-sm font-medium">
                                                    Tanggal
                                                </label>
                                                <input type="datetime-local" name="waktu_transaksi"
                                                    value="{{ date('Y-m-d\TH:i', strtotime($trx->waktu_transaksi)) }}"
                                                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"
                                                    required>
                                            </div>

                                            <!-- Bank -->
                                            <div class="col-span-2">
                                                <label class="block mb-2 text-sm font-medium">
                                                    Bank
                                                </label>
                                                <select name="bank_id"
                                                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"
                                                    required>
                                                    @foreach ($dataBanks as $bank)
                                                        <option value="{{ $bank->id }}"
                                                            {{ $trx->bank_id == $bank->id ? 'selected' : '' }}>
                                                            {{ $bank->nama_bank }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Jenis Transaksi -->
                                            <div class="col-span-2">
                                                <label class="block mb-2 text-sm font-medium">
                                                    Jenis Transaksi
                                                </label>
                                                <select name="jenis_transaksi_id"
                                                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"
                                                    required>
                                                    @foreach ($jenisTransaksis as $jenis)
                                                        <option value="{{ $jenis->id }}"
                                                            {{ $trx->jenis_transaksi_id == $jenis->id ? 'selected' : '' }}>
                                                            {{ $jenis->nama_transaksi }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- No Tujuan -->
                                            <div class="col-span-2">
                                                <label class="block mb-2 text-sm font-medium">
                                                    No. Tujuan
                                                </label>
                                                <input type="text" name="no_tujuan" value="{{ $trx->no_tujuan }}"
                                                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                                            </div>

                                            <!-- Nominal & Bayar -->
                                            <div class="col-span-2">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

                                                    <!-- Nominal -->
                                                    <div>
                                                        <label class="block mb-2 text-sm font-medium">
                                                            Nominal
                                                        </label>
                                                        <input type="number" name="nominal" value="{{ $trx->nominal }}"
                                                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"
                                                            required>
                                                    </div>

                                                    <!-- Bayar -->
                                                    <div>
                                                        <label class="block mb-2 text-sm font-medium">
                                                            Bayar
                                                        </label>
                                                        <input type="number" name="bayar" value="{{ $trx->bayar }}"
                                                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5"
                                                            required>
                                                    </div>

                                                </div>
                                            </div>



                                            <!-- Keterangan -->
                                            <div class="col-span-2">
                                                <label class="block mb-2 text-sm font-medium">
                                                    Keterangan
                                                </label>
                                                <input type="text" name="keterangan" value="{{ $trx->keterangan }}"
                                                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                                            </div>

                                        </div>

                                        <!-- Submit -->
                                        <button type="submit"
                                            class="w-full mt-3 text-white bg-blue-700 hover:bg-blue-800
                                            font-medium rounded-lg text-sm px-5 py-2.5">
                                            Update Transaksi
                                        </button>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">
                            Tidak ada transaksi pada tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
        <a href="{{ route('laporan_bank.rekap', [
            'tanggal' => request('tanggal'),
            'cabang_id' => request('cabang_id'),
            'user_id' => request('user_id'),
        ]) }}"
            class="mt-4 inline-flex items-center gap-2 bg-gradient-to-r bg-blue-600 to-indigo-600 
               text-white px-4 py-2 rounded-lg shadow hover:from-blue-700 hover:to-indigo-700 
               transition duration-300 ease-in-out mt-1">

            <!-- Icon Heroicon: Document Report -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2h6v2H9zm0-4v-2h6v2H9zm0-4V7h6v2H9zM5 3h14a2 2 0
                                                         012 2v14a2 2 0 01-2 2H5a2 2 0
                                                         01-2-2V5a2 2 0 012-2z" />
            </svg>

            <span class="font-semibold">Rekap Laporan</span>
        </a>
    </div>


@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cabangSelect = document.getElementById('cabang_id');
        const userSelect = document.getElementById('user_id');

        cabangSelect.addEventListener('change', function() {
            const cabangId = this.value;
            userSelect.innerHTML = '<option value="">Semua Akun</option>';

            if (cabangId) {
                fetch(`/laporan-bank-admin/get-users-by-cabang/${cabangId}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(user => {
                            const opt = document.createElement('option');
                            opt.value = user.id;
                            opt.textContent = user.name;
                            userSelect.appendChild(opt);
                        });
                    });
            }
        });

        // Untuk tetap menampilkan user setelah reload jika cabang_id terpilih
        const selectedCabang = '{{ request('cabang_id') }}';
        const selectedUser = '{{ request('user_id') }}';
        if (selectedCabang) {
            fetch(`/laporan-bank-admin/get-users-by-cabang/${selectedCabang}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(user => {
                        const opt = document.createElement('option');
                        opt.value = user.id;
                        opt.textContent = user.name;
                        if (user.id == selectedUser) {
                            opt.selected = true;
                        }
                        userSelect.appendChild(opt);
                    });
                });
        }
    });
</script>
