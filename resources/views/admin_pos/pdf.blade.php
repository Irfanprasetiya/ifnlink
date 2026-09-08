<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan POS Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
        }

        h1 {
            text-align: center;
            margin-bottom: 5px;
        }

        .periode {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
            text-transform: uppercase;
            font-size: 8px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-top: 15px;
        }
    </style>
</head>

<body>
    <h1>Laporan Penjualan POS</h1>
    <p class="periode">
        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>ID Transaksi</th>
                <th>Waktu</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Cabang</th>
                <th>Kasir</th>
                <th class="text-right">Harga Total</th>
                <th class="text-right">Diskon</th>
                <th class="text-right">Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($penjualans as $p)
                @foreach ($p->details as $detail)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $p->kode_transaksi ?? 'TRX-' . str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $detail->voucher->nama_produk ?? '-' }}</td>
                        <td>{{ $detail->voucher->kategori->nama_kategori ?? '-' }}</td>
                        <td>{{ $p->cabang->nama_cabang ?? '-' }}</td>
                        <td>{{ $p->user->name ?? '-' }}</td>
                        <td class="text-right">
                            @if ($loop->first)
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($loop->first && $p->diskon > 0)
                                -Rp {{ number_format($p->diskon, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">
                            @if ($loop->first)
                                Rp {{ number_format($p->total_setelah_diskon, 0, ',', '.') }}
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
