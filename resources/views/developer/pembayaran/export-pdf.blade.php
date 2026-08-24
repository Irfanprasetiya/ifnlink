<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Riwayat Pembayaran - Omzetly.id</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        @page {
            size: A4 landscape;
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            font-size: 12px;
            padding: 0;
            color: #111827;
            line-height: 1.5;
            background-color: #ffffff;
            margin: 0;
        }

        /* Utility */
        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-extrabold {
            font-weight: 800;
        }

        .text-muted {
            color: #4b5563;
        }

        .text-dark {
            color: #111827;
        }

        .text-primary {
            color: #2563eb;
        }

        .m-0 {
            margin: 0;
        }

        /* Header */
        .header-wrap {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .brand-name {
            font-size: 24px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.5px;
            margin: 0 0 4px 0;
        }

        .report-title {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
        }

        table.layout {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        table.layout td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        /* Info Bar */
        .info-bar {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 12px 18px;
            margin-bottom: 20px;
        }

        .info-label {
            font-size: 10px;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
            font-weight: 700;
        }

        /* Table Data */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border: 1px solid #e5e7eb;
        }

        table.data-table th {
            background-color: #2563eb;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 10px 12px;
            border: 1px solid #2563eb;
            text-align: left;
            letter-spacing: 0.5px;
        }

        table.data-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            color: #111827;
            font-size: 11px;
        }

        table.data-table td:last-child {
            border-right: none;
        }

        table.data-table tr:nth-child(even) td {
            background-color: #f9fafb;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-success {
            background: #16a34a;
            color: #ffffff;
        }

        .badge-pending {
            background: #d97706;
            color: #ffffff;
        }

        .badge-failed {
            background: #dc2626;
            color: #ffffff;
        }

        .badge-cancelled {
            background: #6b7280;
            color: #ffffff;
        }

        .badge-default {
            background: #e5e7eb;
            color: #374151;
        }

        /* Summary */
        .summary-section {
            margin-top: 25px;
        }

        .summary-box {
            padding: 15px 18px;
            border-radius: 8px;
            text-align: right;
        }

        .box-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .box-total {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .box-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .box-value {
            font-size: 18px;
            font-weight: 800;
        }

        .box-success .box-value {
            color: #15803d;
        }

        .box-total .box-value {
            color: #111827;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header-wrap">
        <table class="layout">
            <tr>
                <td style="width: 50%;">
                    <h1 class="brand-name">OMZETLY.ID</h1>
                    <p class="m-0 font-bold text-dark" style="font-size: 13px;">PT Omzetly Digital Indonesia</p>
                    <p class="m-0 text-muted" style="margin-top: 3px; font-size: 11px;">Jl. Sudimampir Lor No. 21,
                        Indramayu</p>
                    <p class="m-0 text-muted" style="font-size: 11px;">Email: support@omzetly.id | WA: 0838-6609-6623
                    </p>
                </td>
                <td style="width: 50%;" class="text-right">
                    <h2 class="report-title">RIWAYAT PEMBAYARAN</h2>
                    <p class="m-0 font-extrabold text-primary" style="font-size: 14px; margin-bottom: 4px;">
                        Laporan Transaksi Tenant</p>
                    <p class="m-0 text-dark" style="font-size: 11px; margin-bottom: 8px; font-weight: 500;">
                        Generated: {{ now()->format('d F Y H:i:s') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <!-- INFO BAR -->
    <div class="info-bar">
        <table class="layout">
            <tr>
                <td style="width: 33%;">
                    <div class="info-label">Total Data</div>
                    <div class="font-extrabold text-dark" style="font-size: 16px;">{{ $pembayarans->count() }} Transaksi
                    </div>
                </td>
                <td style="width: 33%;">
                    <div class="info-label">Transaksi Sukses</div>
                    <div class="font-extrabold text-primary" style="font-size: 16px;">{{ $totalTransaksiSukses }}
                        Transaksi</div>
                </td>
                <td style="width: 33%;">
                    <div class="info-label">Periode</div>
                    <div class="font-bold text-dark" style="font-size: 13px;">
                        @if (request('start_date') && request('end_date'))
                            {{ date('d/m/Y', strtotime(request('start_date'))) }} -
                            {{ date('d/m/Y', strtotime(request('end_date'))) }}
                        @else
                            Semua Periode
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- TABEL DATA -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%; text-align: center;">No</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 17%;">Order ID</th>
                <th style="width: 16%;">Nama Toko</th>
                <th style="width: 11%;">Paket</th>
                <th style="width: 13%;" class="text-right">Nominal</th>
                <th style="width: 13%;" class="text-center">Metode</th>
                <th style="width: 15%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembayarans as $index => $p)
                <tr>
                    <td class="text-center font-bold">{{ $index + 1 }}</td>
                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td style="font-family: monospace; font-size: 10px; font-weight: 600;">{{ $p->order_id ?? '-' }}
                    </td>
                    <td class="font-bold">{{ $p->tenant->nama_toko ?? '-' }}</td>
                    <td>{{ $p->plan->nama_paket ?? '-' }}</td>
                    <td class="text-right font-bold">Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $p->metode ?? '-' }}</td>
                    <td class="text-center">
                        @if (in_array($p->status, ['confirmed', 'settlement', 'capture', 'success']))
                            <span class="badge badge-success">LUNAS</span>
                        @elseif ($p->status == 'pending')
                            <span class="badge badge-pending">PENDING</span>
                        @elseif ($p->status == 'cancelled')
                            <span class="badge badge-cancelled">BATAL</span>
                        @elseif (in_array($p->status, ['failed', 'deny']))
                            <span class="badge badge-failed">GAGAL</span>
                        @elseif ($p->status == 'expired')
                            <span class="badge badge-cancelled">EXPIRED</span>
                        @else
                            <span class="badge badge-default">{{ strtoupper($p->status) }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada data pembayaran
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- SUMMARY -->
    <table class="layout summary-section">
        <tr>
            <td style="width: 38%;"></td>
            <td style="width: 31%; padding-right: 10px;">
                <div class="summary-box box-success">
                    <div class="box-label">Total Pembayaran Sukses</div>
                    <div class="box-value">Rp {{ number_format($totalJumlahSukses, 0, ',', '.') }}</div>
                </div>
            </td>
            <td style="width: 31%;">
                <div class="summary-box box-total">
                    <div class="box-label">Total Keseluruhan</div>
                    <div class="box-value">Rp {{ number_format($totalJumlahSemua, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        <p style="margin: 0; font-weight: 700; color: #111827; font-size: 12px;">Dokumen ini dihasilkan secara otomatis
            oleh sistem.</p>
        <p style="margin: 5px 0 0 0; color: #4b5563;">&copy; {{ date('Y') }} PT Omzetly Digital Indonesia. All
            Rights Reserved.</p>
    </div>

</body>

</html>
