<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Setoran Harian - Omzetly.id</title>
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
        }

        .doc-subtitle {
            font-size: 9pt;
            color: #64748b;
            font-weight: 400;
            margin: 0;
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

        /* Helpers */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: 700;
        }

        .w-60 {
            width: 60%;
        }

        .w-40 {
            width: 40%;
        }

        .text-negative {
            color: #dc2626;
        }

        /* Total Row */
        .row-total td {
            background-color: #eff6ff !important;
            color: #1e40af;
            font-weight: 800;
            border-top: 2px solid #3b82f6;
            border-bottom: 2px solid #3b82f6;
            font-size: 11pt;
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

            .data-table thead th {
                background-color: #1e293b !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .row-total td {
                background-color: #eff6ff !important;
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

    <!-- Brand Header -->
    <div class="brand-header">
        <div class="brand-logo">Omzetly<span>.id</span></div>
        <div class="brand-tagline">Sistem Informasi Transaksi Agen Jasa Keuangan</div>
    </div>

    <!-- Header Dokumen -->
    <table class="header-table" cellspacing="0" cellpadding="0">
        <tr>
            <td class="w-60">
                <h1 class="doc-title">Laporan Setoran Harian</h1>
                <p class="doc-subtitle">Ringkasan aktivitas kas, mutasi saldo, dan posisi bank</p>
            </td>
            <td class="w-40 meta-info">
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}<br>
                <strong>Cabang:</strong> {{ $user->cabang->nama_cabang ?? '-' }}<br>
                <strong>Operator:</strong> {{ $user->name ?? '-' }}
            </td>
        </tr>
    </table>

    <!-- Tabel 1: Ringkasan Pergerakan Kas -->
    <div class="section-title">Ringkasan Pergerakan Kas</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Saldo Awal Kas</td>
                <td class="text-right">Rp {{ number_format($saldoAwalKas, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tambahan Kas (Setoran/Pemasukan)</td>
                <td class="text-right">Rp {{ number_format($tambahanKas, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pengurangan Kas (Pengeluaran)</td>
                <td class="text-right font-bold text-negative">
                    (Rp {{ number_format($penguranganKas, 0, ',', '.') }})
                </td>
            </tr>
            <tr>
                <td>Total Transfer Masuk</td>
                <td class="text-right">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Tarik Tunai</td>
                <td class="text-right">Rp {{ number_format($totalTarikTunai, 0, ',', '.') }}</td>
            </tr>
            <tr class="row-total">
                <td>SALDO AKHIR KAS</td>
                <td class="text-right">Rp {{ number_format($saldoAkhirKas, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Tabel 2: Posisi Saldo Bank -->
    <div class="section-title">Posisi Saldo Bank</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Bank / Rekening</th>
                <th class="text-right">Total Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($saldoBank as $bank => $saldo)
                @if (strtolower($bank) !== 'kas')
                    <tr>
                        <td class="font-bold">{{ strtoupper($bank) }}</td>
                        <td class="text-right">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="2" class="text-center" style="color: #94a3b8; font-style: italic;">
                        Tidak ada data saldo bank terdaftar.
                    </td>
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
