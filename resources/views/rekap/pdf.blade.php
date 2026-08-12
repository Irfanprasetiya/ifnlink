<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Laporan Rekap - {{ $tanggal }}</title>
    <style>
        /* Reset & Global */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 30px 25px;
        }

        /* Brand Header */
        .brand-header {
            width: 100%;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 16px;
            margin-bottom: 8px;
        }

        .brand-logo {
            font-size: 22pt;
            font-weight: 900;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .brand-logo span {
            color: #2563eb;
        }

        .brand-tagline {
            font-size: 8pt;
            color: #64748b;
            font-weight: 400;
            margin-top: 2px;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 14px;
            margin-bottom: 20px;
        }

        .header-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }

        .doc-title {
            font-size: 16pt;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: 0.3px;
            margin: 0 0 4px 0;
            text-transform: uppercase;
        }

        .doc-subtitle {
            font-size: 9pt;
            color: #64748b;
            font-weight: 400;
            margin: 0;
            line-height: 1.4;
        }

        .doc-subtitle strong {
            color: #1e293b;
        }

        .meta-info {
            text-align: right;
            font-size: 9pt;
            color: #475569;
            line-height: 1.8;
        }

        .meta-info strong {
            color: #1e293b;
            font-weight: 700;
        }

        /* KPI Cards (Ringkasan) */
        .kpi-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .kpi-card {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .kpi-label {
            font-size: 7.5pt;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .kpi-value {
            font-size: 13pt;
            font-weight: 800;
        }

        .kpi-sub {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 4px;
        }

        /* Section Title */
        .section-title {
            font-size: 12pt;
            font-weight: 800;
            color: #1e293b;
            margin: 20px 0 10px 0;
            padding-left: 8px;
            border-left: 4px solid #2563eb;
        }

        /* Data Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .data-table th,
        .data-table td {
            padding: 10px 12px;
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

        .data-table thead th:first-child {
            border-radius: 6px 0 0 0;
        }

        .data-table thead th:last-child {
            border-radius: 0 6px 0 0;
        }

        .data-table tbody td {
            border-bottom: 1px solid #e2e8f0;
            font-size: 10.5pt;
        }

        .data-table tbody tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        /* Utilities */
        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .font-bold {
            font-weight: 700 !important;
        }

        .w-60 {
            width: 60%;
        }

        .w-40 {
            width: 40%;
        }

        .blue {
            color: #2563eb;
        }

        .green {
            color: #16a34a;
        }

        .red {
            color: #dc2626;
        }

        .indigo {
            color: #4f46e5;
        }

        .purple {
            color: #7c3aed;
        }

        .text-negative {
            color: #dc2626;
        }

        /* Brand Footer */
        .brand-footer {
            position: fixed;
            bottom: -15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 7.5pt;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            line-height: 1.6;
        }

        .brand-footer strong {
            color: #2563eb;
            font-weight: 700;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 20px 15px;
                font-size: 10.5pt;
            }

            .kpi-card {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .data-table thead th {
                background-color: #1e293b !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .data-table tbody tr:nth-child(even) td {
                background-color: #f8fafc !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    @php
        $totalNonKas = ($totalTransfer ?? 0) + ($totalTarikTunai ?? 0) + ($totalNumpang ?? 0);
        $rataOmzet = $totalNonKas > 0 ? ($totalOmzet ?? 0) / $totalNonKas : 0;
    @endphp

    <!-- Brand Header -->
    <div class="brand-header">
        <div class="brand-logo">Omzetly<span>.id</span></div>
        <div class="brand-tagline">Sistem Informasi Transaksi Agen Jasa Keuangan</div>
    </div>

    <!-- Header Dokumen -->
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="w-60">
                <h1 class="doc-title">Laporan Rekapitulasi</h1>
                <p class="doc-subtitle">
                    <strong>{{ $tenant->nama_toko ?? 'Nama Toko / Agen' }}</strong><br>
                    {{-- {{ $tenant->alamat ?? 'Alamat toko belum diatur' }} --}}
                </p>
            </td>
            <td class="w-40 meta-info">
                <strong>Periode:</strong> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}<br>
                @if (isset($cabang_terpilih))
                    <strong>Cabang:</strong> {{ $cabang_terpilih }}
                @else
                    <strong>Cabang:</strong> Semua Cabang
                @endif
            </td>
        </tr>
    </table>

    <!-- RINGKASAN KEUANGAN (4 KPI BOXES) -->
    <table class="kpi-table" cellspacing="0" cellpadding="0">
        <tr>
            {{-- Omzet --}}
            <td class="kpi-card" style="width: 23%;">
                <div class="kpi-label">Total Profit</div>
                <div class="kpi-value blue">Rp {{ number_format($totalOmzet ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Total pendapatan admin</div>
            </td>
            <td style="width: 2.6%;"></td>

            {{-- Total Transaksi Non-Kas --}}
            <td class="kpi-card" style="width: 23%;">
                <div class="kpi-label">Volume Trx</div>
                <div class="kpi-value indigo">{{ number_format($totalNonKas ?? 0) }}</div>
                <div class="kpi-sub">TF + Tarik + Numpang</div>
            </td>
            <td style="width: 2.6%;"></td>

            {{-- Saldo Kas --}}
            <td class="kpi-card" style="width: 23%;">
                <div class="kpi-label">Saldo Kas</div>
                <div class="kpi-value purple">Rp {{ number_format($totalSaldoKas ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Total akhir uang fisik</div>
            </td>
            <td style="width: 2.6%;"></td>

            {{-- Rata-rata Profit --}}
            <td class="kpi-card" style="width: 23%;">
                <div class="kpi-label">Rata Profit/Trx</div>
                <div class="kpi-value green">Rp {{ number_format($rataOmzet ?? 0, 0, ',', '.') }}</div>
                <div class="kpi-sub">Profit / Trx Non-Kas</div>
            </td>
        </tr>
    </table>

    <!-- RINGKASAN TRANSAKSI -->
    <div class="section-title">Ringkasan Volume Transaksi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">Total Transaksi</th>
                <th class="text-center">Transfer</th>
                <th class="text-center">Tarik Tunai</th>
                <th class="text-center">Numpang TF</th>
                <th class="text-center">Tambah Kas</th>
                <th class="text-center">Kurang Kas</th>
            </tr>
        </thead>
        <tbody>
            <tr class="text-center">
                <td class="font-bold">{{ number_format($totalTransaksi ?? 0) }}</td>
                <td>{{ number_format($totalTransfer ?? 0) }}</td>
                <td>{{ number_format($totalTarikTunai ?? 0) }}</td>
                <td>{{ number_format($totalNumpang ?? 0) }}</td>
                <td>{{ number_format($totalPenambahanKas ?? 0) }}</td>
                <td>{{ number_format($totalPenguranganKas ?? 0) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- REKAP PER BANK -->
    <div class="section-title">Rekapitulasi Saldo per Bank</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Bank / Channel</th>
                <th class="text-center">Jml Transaksi</th>
                <th class="text-right">Total Debit</th>
                <th class="text-right">Total Kredit</th>
                <th class="text-right">Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapBank as $b)
                <tr>
                    <td class="font-bold">{{ strtoupper($b['nama']) }}</td>
                    <td class="text-center">{{ $b['total_trx'] }}</td>
                    <td class="text-right">Rp {{ number_format($b['debit'], 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($b['kredit'], 0, ',', '.') }}</td>
                    <td class="text-right">
                        <strong class="{{ $b['saldo'] >= 0 ? 'blue' : 'text-negative' }}">
                            Rp {{ number_format($b['saldo'], 0, ',', '.') }}
                        </strong>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="color: #94a3b8; font-style: italic;">Tidak ada data
                        bank</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- REKAP PER CABANG -->
    <div class="section-title">Rekapitulasi Kinerja per Cabang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Cabang / Toko</th>
                <th class="text-center">Jml Transaksi</th>
                <th class="text-right">Total Profit</th>
                <th class="text-right">Saldo Kas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapCabang as $c)
                <tr>
                    <td class="font-bold">{{ $c['nama'] }}</td>
                    <td class="text-center">{{ $c['total_trx'] }}</td>
                    <td class="text-right font-bold blue">Rp {{ number_format($c['omzet'], 0, ',', '.') }}</td>
                    <td class="text-right font-bold purple">Rp {{ number_format($c['saldo_kas'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="color: #94a3b8; font-style: italic;">Tidak ada data
                        cabang</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- REKAP PER USER -->
    <div class="section-title">Rekapitulasi Kinerja Operator</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Operator</th>
                <th>Cabang</th>
                <th class="text-center">Jml Transaksi</th>
                <th class="text-right">Profit Kontribusi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekapUser as $u)
                <tr>
                    <td class="font-bold">{{ $u['nama'] }}</td>
                    <td>{{ $u['cabang'] }}</td>
                    <td class="text-center">{{ number_format($u['total_trx']) }}</td>
                    <td class="text-right font-bold blue">Rp {{ number_format($u['omzet'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="color: #94a3b8; font-style: italic;">Tidak ada data
                        operator</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Brand Footer -->
    <div class="brand-footer">
        Dokumen ini dicetak dari <strong>Omzetly.id</strong> — Sistem Informasi Transaksi Agen Jasa Keuangan<br>
        {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }} WIB &nbsp;|&nbsp; &copy; {{ date('Y') }} PT
        Omzetly Digital Indonesia
    </div>

</body>

</html>
