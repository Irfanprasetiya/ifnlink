@extends('layouts.app')

@section('title', 'Detail Retur')

@section('container')
    <div class="space-y-6 pb-12 max-w-3xl mx-auto">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h1 class="text-xl font-extrabold text-slate-900">Detail Retur</h1>
            <p class="text-sm text-slate-500 mt-1 font-mono">{{ $retur->kode_retur }}</p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 space-y-4">
            <div class="flex justify-between">
                <span class="text-slate-500">Cabang</span>
                <span class="font-bold">{{ $retur->cabang->nama_cabang ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">User</span>
                <span class="font-bold">{{ $retur->user->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status</span>
                @if ($retur->status == 'pending')
                    <span class="font-bold text-amber-600">PENDING</span>
                @elseif($retur->status == 'approved')
                    <span class="font-bold text-emerald-600">APPROVED</span>
                @else
                    <span class="font-bold text-rose-600">REJECTED</span>
                @endif
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Total Refund</span>
                <span class="font-bold text-blue-600">Rp {{ number_format($retur->total_refund, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Alasan</span>
                <span class="font-bold">{{ $retur->alasan ?? '-' }}</span>
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    @foreach ($retur->details as $d)
                        <tr>
                            <td class="px-4 py-3 font-bold">{{ $d->voucher->nama_produk ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">{{ $d->qty }}</td>
                            <td class="px-4 py-3 text-right">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tombol Approve/Reject --}}
        @if ($retur->status == 'pending')
            <div class="flex gap-3">
                <form action="{{ route('admin.retur.approve', $retur->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" onclick="return confirm('Approve retur ini? Stok akan dikembalikan.')"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl">
                        Approve
                    </button>
                </form>
                <form action="{{ route('admin.retur.reject', $retur->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" onclick="return confirm('Tolak retur ini?')"
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3 rounded-xl">
                        Reject
                    </button>
                </form>
            </div>
        @endif

        <a href="{{ route('admin.retur.index') }}" class="inline-block text-blue-600 font-bold">← Kembali</a>
    </div>
@endsection
