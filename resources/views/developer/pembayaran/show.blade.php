@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('container')
    <div class="max-w-2xl mx-auto space-y-6 pb-12 mt-5">

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h1 class="text-xl font-extrabold text-slate-900 mb-6">Detail Pembayaran</h1>

            <div class="space-y-4">
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Nama Toko</span>
                    <span class="font-bold">{{ $pembayaran->tenant->nama_toko ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Nama Pemilik</span>
                    <span class="font-bold">{{ $pembayaran->tenant->nama_pemilik ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Paket</span>
                    <span class="font-bold">{{ $pembayaran->plan->nama_paket ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Nominal</span>
                    <span class="font-bold text-blue-600">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Order ID</span>
                    <span class="font-mono">{{ $pembayaran->order_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Transaction ID</span>
                    <span class="font-mono">{{ $pembayaran->transaction_id ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Metode Bayar</span>
                    <span class="font-bold">{{ $pembayaran->metode ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Status</span>
                    <span
                        class="font-bold 
                    @if (in_array($pembayaran->status, ['confirmed', 'settlement', 'capture', 'success'])) text-emerald-600
                    @elseif($pembayaran->status == 'pending')
                        text-amber-600
                    @else
                        text-rose-600 @endif">
                        {{ strtoupper($pembayaran->status) }}
                    </span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Keterangan</span>
                    <span class="font-bold text-right">{{ $pembayaran->keterangan ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-b pb-2">
                    <span class="text-slate-500">Tanggal Bayar</span>
                    <span class="font-bold">
                        {{ $pembayaran->tanggal_bayar ? $pembayaran->tanggal_bayar->format('d F Y H:i') : '-' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tanggal Konfirmasi</span>
                    <span class="font-bold">
                        {{ $pembayaran->tanggal_konfirmasi ? $pembayaran->tanggal_konfirmasi->format('d F Y H:i') : '-' }}
                    </span>
                </div>
            </div>

            <a href="{{ route('developer.pembayaran.index') }}"
                class="mt-6 inline-block text-blue-600 hover:text-blue-800 font-bold text-sm">
                ← Kembali
            </a>
        </div>
    </div>
@endsection
