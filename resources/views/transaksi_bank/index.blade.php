@extends('layouts.app')

@section('title', 'Transaksi Bank')

@section('container')
    <div class=" container mx-auto px-4 mt-6">
        <h1 class="mt-3 mb-5 text-xl font-bold">Data Transaksi Bank</h1>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-4 gap-4">

            {{-- Filter di kiri --}}
            <form method="GET" action="{{ route('trx-bank.index') }}"
                class="flex flex-col sm:flex-row flex-wrap gap-3 w-full md:w-auto">

                <div>
                    <label for="tanggal" class="block text-sm">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal"
                        value="{{ request('tanggal', \Carbon\Carbon::now()->toDateString()) }}"
                        class="border rounded px-3 py-2 w-full md:w-40">
                </div>

                <div>
                    <label for="cabang_id" class="block text-sm">Cabang</label>
                    <select name="cabang_id" id="cabang_id" class="border rounded px-3 py-2 w-full md:w-40">
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
                    <select name="user_id" id="user_id" class="border rounded px-3 py-2 w-full md:w-40">
                        <option value="">Semua Akun</option>
                        {{-- diisi lewat JS --}}
                    </select>
                </div>

                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-5  w-full md:w-auto">
                        Cari
                    </button>
                </div>
            </form>

            {{-- Button tambah/kurang saldo di kanan --}}
            <div class="flex gap-3 w-full md:w-auto">
                <button onclick="openModal('penambahan')"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    + Tambah Saldo
                </button>

                <button onclick="openModal('pengeluaran')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded">
                    - Kurangi Saldo
                </button>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-200 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif


    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="text-xs uppercase bg-blue-700 text-white">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2 text-left">Nama Bank</th>
                    <th class="px-4 py-2 text-right">Saldo</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($banks as $bank)
                    @php
                        $saldo = $saldoBank[$bank->id] ?? 0;
                    @endphp

                    <tr class="border-b hover:bg-gray-50">

                        <!-- No -->
                        <td class="px-4 py-3 text-center">
                            {{ $no++ }}
                        </td>

                        <!-- Nama Bank -->
                        <td class="px-4 py-3">
                            {{ $bank->nama_bank }}
                        </td>

                        <!-- Saldo Realtime -->
                        <td class="px-4 py-3 text-right font-semibold">
                            Rp {{ number_format($saldoBank[strtolower($bank->nama_bank)] ?? 0, 0, ',', '.') }} </td>
                        <!-- Status -->
                        <td class="px-4 py-3 text-center">
                            @if ($statusBank[$bank->id] === 'Active')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                    Active
                                </span>
                            @else
                                <span class="bg-gray-200 text-gray-500 px-3 py-1 rounded-full text-sm font-semibold">
                                    Disable
                                </span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>




        </table>
    </div>

    <!-- Modal Tambah Saldo -->
    <div id="modalTambahSaldo" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">

        <div class="bg-white rounded-lg shadow p-6 w-full max-w-lg">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Input Saldo Awal</h3>
                <button onclick="document.getElementById('modalTambahSaldo').classList.add('hidden')"
                    class="text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            </div>

            <form action="{{ route('trx-bank.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <input type="hidden" name="jenis_transaksi" id="jenis_transaksi" value="Penambahan Saldo">
                </div>
                <!-- Cabang -->
                <div>
                    <label class="block text-sm font-medium mb-1">Pilih Cabang</label>
                    <select id="modal_cabang_id" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nama User</label>
                    <select name="user_id" id="modal_user_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Pilih User</option>
                    </select>
                </div>

                <!-- Bank -->
                <div>
                    <label class="block text-sm font-medium">Pilih Bank</label>
                    <select id="modal_bank_id" name="bank_id" required
                        class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Pilih Bank</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->nama_bank }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-sm font-medium">Nominal Saldo</label>
                    <input id="nominal_input" type="number" name="nominal"
                        class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Masukkan saldo awal">
                </div>

                <!-- Jenis Transaksi -->
                <div id="saldoAwalBox">
                    <input type="checkbox" name="is_saldo_awal" id="is_saldo_awal" value="1" checked>
                    <label for="is_saldo_awal">Saldo Awal</label>
                </div>

                <!-- Waktu -->
                <input type="hidden" name="waktu_transaksi" value="{{ now() }}">

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium">Keterangan</label>
                    <textarea id="keterangan_input" name="keterangan" rows="2"
                        class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Saldo awal shift"></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.getElementById('modal_user_id').addEventListener('change', cekSaldoAwal);
        document.getElementById('modal_bank_id').addEventListener('change', cekSaldoAwal);

        function cekSaldoAwal() {

            let user = document.getElementById('modal_user_id').value;
            let bank = document.getElementById('modal_bank_id').value;

            if (user && bank) {

                fetch("{{ route('cek.saldo.awal.bank') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            user_id: user,
                            bank_id: bank
                        })
                    })
                    .then(res => res.json())
                    .then(res => {

                        let box = document.getElementById('saldoAwalBox');
                        let nominal = document.getElementById('nominal_input');
                        let ket = document.getElementById('keterangan_input');
                        let chk = document.getElementById('is_saldo_awal');

                        if (res.status) {
                            // SUDAH ADA SALDO AWAL
                            box.style.display = 'none';

                            chk.checked = false;
                            chk.disabled = true;

                            nominal.placeholder = "Input saldo";
                            ket.placeholder = "Opsional";

                        } else {
                            // BELUM ADA
                            box.style.display = 'block';

                            chk.disabled = false;
                            chk.checked = true;

                            nominal.placeholder = "Masukkan saldo awal";
                            ket.placeholder = "Saldo awal shift";
                        }
                    });
            }
        }
    </script>





    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const cabangSelect = document.getElementById('modal_cabang_id');
            const userSelect = document.getElementById('modal_user_id');

            function loadUsers(cabangId) {

                userSelect.innerHTML = '<option value="">Pilih User</option>';

                if (cabangId) {
                    fetch(`/get-users-by-cabang/${cabangId}`)
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
            }

            cabangSelect.addEventListener('change', function() {
                loadUsers(this.value);
            });

        });
    </script>


    <!-- script ajax untuk filter -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cabangSelect = document.getElementById('cabang_id');
            const userSelect = document.getElementById('user_id');
            const selectedUserId = "{{ $user_id }}";

            function loadUsers(cabangId, selectedId = '') {
                userSelect.innerHTML = '<option value="">Semua Akun</option>';
                if (cabangId) {
                    fetch(`/get-users-by-cabang/${cabangId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(user => {
                                const option = document.createElement('option');
                                option.value = user.id;
                                option.textContent = user.name;
                                if (selectedId && selectedId == user.id) {
                                    option.selected = true;
                                }
                                userSelect.appendChild(option);
                            });
                        });
                }
            }

            cabangSelect.addEventListener('change', function() {
                loadUsers(this.value);
            });

            if (cabangSelect.value) {
                loadUsers(cabangSelect.value, selectedUserId);
            }
        });


        // modal tambah dan kurang
        function openModal(type) {
            const modal = document.getElementById('modalTambahSaldo');
            modal.classList.remove('hidden');

            // set hidden field jenis_transaksi
            document.getElementById('jenis_transaksi').value = type;

            // ubah judul modal
            document.querySelector('#modalTambahSaldo h3').innerText =
                type === 'penambahan' ? "Tambah Saldo" : "Kurangi Saldo";

            // tampilkan/hidden checkbox saldo awal
            if (type === 'penambahan') {
                document.getElementById('saldoAwalBox').classList.remove('hidden');
            } else {
                document.getElementById('saldoAwalBox').classList.add('hidden');
            }
        }
    </script>
@endsection
