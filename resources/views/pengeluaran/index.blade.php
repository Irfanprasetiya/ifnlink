@extends('layouts.app')

@section('title', 'Pengeluaran')

@section('container')
    <div class=" container mx-auto px-4 mt-6">

        <h1 class="mt-3 mb-5 text-xl font-bold">Data Pengeluaran Kas</h1>

        <div class="flex flex-wrap justify-between items-center mb-4">
            <form method="GET" action="{{ route('pengeluaran.index') }}" class="flex flex-wrap gap-3 items-end mb-4">

                <div>
                    <label class="block text-sm">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                        class="border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm">Cabang</label>
                    <select name="cabang_id" class="border rounded px-3 py-2">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabangs as $c)
                            <option value="{{ $c->id }}" {{ request('cabang_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Cari
                </button>

            </form>


            <button data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                class="mb-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                + Pengeluaran Kas
            </button>
        </div>
    </div>

    <!-- table -->
    <div class="relative overflow-x-auto mt-5">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-white uppercase bg-blue-700">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Cabang</th>
                    <th class="px-6 py-3">Akun</th>
                    <th class="px-6 py-3">Nominal</th>
                    <th class="px-6 py-3">Keterangan</th>
                    <th class="px-6 py-3">Action</th>
                </tr>
            </thead>

            <tbody>

                @if (!request()->hasAny(['tanggal', 'cabang_id']))
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 font-semibold">
                            📌 Silakan pilih cabang dan tanggal untuk menampilkan data
                        </td>
                    </tr>
                @elseif($data->count() == 0)
                    <tr>
                        <td colspan="5" class="text-center py-6 text-red-500 font-semibold">
                            ❌ Data tidak ditemukan
                        </td>
                    </tr>
                @else
                    @foreach ($data as $item)
                        <tr class="bg-white even:bg-gray-100">
                            <td class="px-6 py-4">{{ $item->tanggal }}</td>
                            <td class="px-6 py-4">{{ $item->cabang->nama_cabang }}</td>
                            <td class="px-6 py-4">{{ $item->akun->nama_akun }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->nominal) }}</td>
                            <td class="px-6 py-4">{{ $item->keterangan }}</td>

                            <td class="px-6 py-4 flex gap-2">

                                <button type="button" data-modal-target="edit-modal-{{ $item->id }}"
                                    data-modal-toggle="edit-modal-{{ $item->id }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-xs">
                                    Edit
                                </button>

                                <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus data?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>

        </table>
    </div>

    </div>

    @include('pengeluaran.modal-tambah')
@endsection
