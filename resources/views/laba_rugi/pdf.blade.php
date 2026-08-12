<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi Per Cabang</title>
    <style>
        /* Reset & Global */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.5;
            padding: 30px 25px;
        }

        /* Brand Header */
        .brand-header {
            width: 100%;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .brand-logo {
            font-size: 20pt;
            font-weight: 900;
            color: #1e293b;
        }

        .brand-logo span {
            color: #2563eb;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .doc-title {
            font-size: 14pt;
            font-weight: 800;
            color: #1e293b;
            text-transform: uppercase;
        }

        .doc-subtitle {
            font-size: 9pt;
            color: #64748b;
            margin-top: 4px;
        }

        .doc-subtitle strong {
            color: #1e293b;
        }

        .meta-info {
            text-align: right;
            font-size: 9pt;
            color: #475569;
        }

        .meta-info span {
            font-weight: 700;
            color: #1e293b;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th,
        .data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table thead th {
            background-color: #1e293b;
            color: #ffffff;
            font-size: 8.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Status Colors */
        .profit {
            color: #059669;
            font-weight: 700;
        }

        .rugi {
            color: #dc2626;
            font-weight: 700;
        }

        /* Total Row */
        .total-row td {
            background-color: #eff6ff !important;
            color: #1e40af;
            font-weight: 800;
            border-top: 2px solid #3b82f6;
            border-bottom: 2px solid #3b82f6;
            font-size: 10.5pt;
        }

        .footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="brand-header">
        <div class="brand-logo">Omzetly<span>.id</span></div>
    </div>

    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <h1 class="doc-title">Laporan Laba Rugi Per Cabang</h1>
                <p class="doc-subtitle">
                    <strong>{{ $tenant->nama_toko ?? (Auth::user()->tenant->nama_toko ?? 'Nama Toko / Agen') }}</strong>
                    — Rekapitulasi profit & laba bersih operasional
                </p>
            </td>
            <td class="meta-info">
                Periode: <span>{{ \Carbon\Carbon::parse($tanggalAwal)->format('d-m-Y') }} s/d
                    {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d-m-Y') }}</span>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Cabang</th>
                <th class="text-right">Laba Bersih</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cabangs as $cabang)
                @php
                    $bersih = $labaBersih[$cabang->id] ?? 0;
                @endphp
                <tr>
                    <td class="font-bold">{{ $cabang->nama_cabang }}</td>
                    <td class="text-right {{ $bersih >= 0 ? 'profit' : 'rugi' }}">
                        Rp {{ number_format($bersih, 0, ',', '.') }}
                    </td>
                    <td class="text-center {{ $bersih >= 0 ? 'profit' : 'rugi' }}">
                        {{ $bersih >= 0 ? 'PROFIT' : 'RUGI' }}
                    </td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td>TOTAL KESELURUHAN</td>
                <td class="text-right">Rp {{ number_format($totalLabaBersih, 0, ',', '.') }}</td>
                <td class="text-center">{{ $totalLabaBersih >= 0 ? 'PROFIT' : 'RUGI' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak otomatis dari sistem
        <strong>{{ $tenant->nama_toko ?? (Auth::user()->tenant->nama_toko ?? 'Omzetly.id') }}</strong> pada
        {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB
    </div>

</body>

</html>
