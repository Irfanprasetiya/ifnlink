@extends('layouts.app')

@section('container')
    <div class="overflow-x-auto mt-6">
        <form method="GET" action="{{ route('saldo_gudang.index') }}" class="flex flex-wrap gap-3 items-end mb-4">
            <div>
                <label for="tanggal" class="block text-sm">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal"
                    value="{{ request('tanggal', \Carbon\Carbon::now()->toDateString()) }}" class="border rounded px-3 py-2">
            </div>

            <div>
                <label for="cabang_id" class="block text-sm">Cabang</label>
                <select name="cabang_id" id="cabang_id" class="border rounded px-3 py-2">
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
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" {{ $user_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-1">Cari</button>
            </div>
        </form>

        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead class="text-xs uppercase bg-gradient-to-r bg-blue-600 to-indigo-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-center">#</th>
                    <th class="px-4 py-3 text-left">Nama Bank</th>
                    <th class="px-4 py-3 text-right">Saldo</th>
                    <th class="px-4 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php $no = 1; @endphp
                @foreach ($banks as $bank)
                    @php
                        $saldo = $saldoBank[$bank->id] ?? 0;
                        $status = $statusBank[$bank->id] ?? 'Disable';
                    @endphp
                    <tr class="even:bg-gray-50 hover:bg-gray-100 transition-colors">
                        <!-- No -->
                        <td class="px-4 py-3 text-center">{{ $no++ }}</td>

                        <!-- Nama Bank -->
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $bank->nama_bank }}</td>

                        <!-- Saldo -->
                        <td class="px-4 py-3 text-right font-semibold">
                            Rp {{ number_format($saldoBank[strtolower($bank->nama_bank)] ?? 0, 0, ',', '.') }} </td>

                        <!-- Status -->
                        <td class="px-4 py-3 text-center">
                            @if ($status === 'Active')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Active
                                </span>
                            @else
                                <span class="bg-gray-200 text-gray-500 px-3 py-1 rounded-full text-xs font-semibold">
                                    Disable
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
