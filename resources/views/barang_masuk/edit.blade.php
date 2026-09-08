@extends('layouts.app')

@section('title', 'Edit Barang Masuk')

@section('container')
    <div class="max-w-2xl mx-auto space-y-6 pb-32 mt-4">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h1 class="text-lg font-extrabold text-slate-800">Edit Barang Masuk</h1>
        </div>

        <form action="{{ route('barang_masuk.update', $barangMasuk->id) }}" method="POST"
            class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="text-xs font-bold uppercase text-slate-500">Produk & Cabang</label>
                <select name="produk_konter_id" required class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm mt-1">
                    @foreach ($produkKonters as $pk)
                        <option value="{{ $pk->id }}"
                            {{ $barangMasuk->produk_konter_id == $pk->id ? 'selected' : '' }}>
                            {{ $pk->voucher->nama_produk }} - {{ $pk->cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-slate-500">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $barangMasuk->tanggal->format('Y-m-d') }}" required
                    class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm mt-1">
            </div>

            <div>
                <label class="text-xs font-bold uppercase text-slate-500">Qty</label>
                <input type="number" name="qty" value="{{ $barangMasuk->qty }}" min="1" required
                    class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm mt-1">
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl">
                Update
            </button>
        </form>
    </div>
@endsection
