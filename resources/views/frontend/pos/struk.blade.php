<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $penjualan->kode_transaksi ?? $penjualan->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .struk-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div
        class="struk-container bg-white rounded-2xl shadow-lg border border-slate-200 max-w-xs w-full p-6 font-mono text-sm">

        {{-- Header --}}
        <div class="text-center mb-4">
            <h1 class="font-bold text-lg">{{ $penjualan->tenant->nama_toko ?? 'OMZETLY.ID' }}</h1>
            <p class="text-xs">{{ $penjualan->cabang->nama_cabang ?? 'Cabang' }}</p>
            <p class="text-xs text-slate-500">{{ $penjualan->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Info Transaksi --}}
        <div class="space-y-1 text-xs mb-4">
            <div class="flex justify-between">
                <span>No. Transaksi</span>
                <span class="font-bold">{{ $penjualan->kode_transaksi ?? 'TRX-' . $penjualan->id }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir</span>
                <span>{{ $penjualan->user->name ?? '-' }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Items --}}
        <div class="space-y-2 mb-4">
            @foreach ($penjualan->details as $d)
                <div class="flex justify-between text-xs">
                    <div>
                        <p class="font-bold">{{ $d->voucher->nama_produk }}</p>
                        <p class="text-slate-500">{{ $d->qty }} x Rp
                            {{ number_format($d->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                    <span class="font-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Total --}}
        <div class="space-y-1 text-sm mb-4">
            <div class="flex justify-between">
                <span>Total</span>
                <span>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
            </div>
            @if ($penjualan->diskon > 0)
                <div class="flex justify-between text-rose-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-base border-t border-slate-200 pt-2">
                <span>Grand Total</span>
                <span>Rp {{ number_format($penjualan->total_setelah_diskon, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Bayar</span>
                <span>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Kembalian</span>
                <span>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Footer --}}
        <div class="text-center text-xs">
            <p class="font-bold">Terima Kasih!</p>
            <p class="text-slate-500">Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>

        {{-- Tombol Print --}}
        <button onclick="window.print()"
            class="no-print w-full mt-4 bg-blue-600 text-white font-bold py-2.5 rounded-lg text-sm">
            Cetak Struk
        </button>
    </div>
</body>

</html>
