<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan POS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1>Laporan Penjualan POS</h1>
    <p style="text-align:center">
        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Transaksi</th>
                <th>Waktu</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
                <th>Diskon</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($penjualans as $p)
                @foreach ($p->details as $d)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $p->kode_transaksi ?? 'TRX-' . str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $d->voucher->nama_produk ?? '-' }}</td>
                        <td>{{ $d->voucher->kategori->nama_kategori ?? '-' }}</td>
                        <td class="text-right">{{ $d->qty }}</td>
                        <td class="text-right">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                        <td class="text-right">
                            @if ($loop->first)
                                @if ($p->diskon > 0)
                                    - Rp {{ number_format($p->diskon, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($loop->first)
                                Rp {{ number_format($p->total_setelah_diskon, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p><strong>Total Transaksi:</strong> {{ $totalTransaksi }}</p>
        <p><strong>Total Produk Terjual:</strong> {{ $totalProdukTerjual }}</p>
        <p><strong>Total Penjualan (Setelah Diskon):</strong> Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
    </div>
</body>

</html>
