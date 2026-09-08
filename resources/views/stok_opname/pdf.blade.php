<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Opname {{ $opname->kode_opname }}</title>
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
    </style>
</head>

<body>
    <h1>Laporan Stok Opname</h1>
    <p style="text-align:center">
        {{ $opname->cabang->nama_cabang ?? '-' }} • {{ $opname->tanggal_opname->format('d/m/Y') }}
    </p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Qty</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($details as $detail)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $detail->voucher->nama_produk ?? '-' }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>{{ $detail->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
