<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Invoice #INV-{{ str_pad($pembayaran->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* Menggunakan Font Inter (SaaS Standard) */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', Helvetica, Arial, sans-serif;
            font-size: 13px;
            padding: 40px;
            color: #111827;
            /* Hitam tegas, bukan abu-abu pucat */
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

        /* Warna Teks Disesuaikan (Lebih kontras) */
        .text-muted {
            color: #4b5563;
        }

        /* Abu-abu gelap untuk keterbacaan tinggi */
        .text-dark {
            color: #111827;
        }

        .text-primary {
            color: #2563eb;
        }

        .m-0 {
            margin: 0;
        }

        /* Tabel Layout (Untuk kompatibilitas PDF DomPDF) */
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

        /* Header */
        .header-wrap {
            border-bottom: 2px solid #2563eb;
            /* Garis bawah biru tegas */
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .brand-name {
            font-size: 26px;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: -0.5px;
            margin: 0 0 4px 0;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: 800;
            color: #111827;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
        }

        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-success {
            background: #16a34a;
            color: #ffffff;
        }

        /* Hijau solid & putih */
        .badge-pending {
            background: #d97706;
            color: #ffffff;
        }

        /* Kuning/Oranye solid */
        .badge-failed {
            background: #dc2626;
            color: #ffffff;
        }

        /* Merah solid */

        /* Section Info */
        .info-box {
            background-color: #eff6ff;
            /* Biru super muda / Ice Blue */
            border: 1px solid #bfdbfe;
            /* Border biru muda */
            border-radius: 8px;
            padding: 18px;
        }

        .section-label {
            font-size: 11px;
            color: #2563eb;
            /* Label warna biru utama */
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        /* Tabel Item */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            margin-bottom: 20px;
            border: 1px solid #e5e7eb;
        }

        table.items th {
            background-color: #2563eb;
            /* Header tabel biru solid */
            color: #ffffff;
            /* Teks putih */
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 12px 15px;
            border: 1px solid #2563eb;
            text-align: left;
        }

        table.items td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            color: #111827;
        }

        table.items td:last-child {
            border-right: none;
        }

        table.items .total-row td {
            border: none;
            padding-top: 12px;
            padding-bottom: 4px;
        }

        /* Payment Details Box */
        .payment-meta {
            margin-top: 30px;
            border-top: 2px dashed #cbd5e1;
            padding-top: 20px;
        }

        .payment-meta table td {
            padding: 4px 0;
            font-size: 12px;
            color: #374151;
            /* Gelap tegas */
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <!-- HEADER (Kiri: Info Perusahaan, Kanan: Info Invoice) -->
    <div class="header-wrap">
        <table class="layout">
            <tr>
                <td style="width: 50%;">
                    <h1 class="brand-name">OMZETLY.ID</h1>
                    <p class="m-0 font-bold text-dark" style="font-size: 14px;">PT Omzetly Digital Indonesia</p>
                    <p class="m-0 text-muted" style="margin-top: 4px; font-size: 12px;">Jl. Sudimampir Lor No. 21,
                        Indramayu</p>
                    <p class="m-0 text-muted" style="font-size: 12px;">Email: support@omzetly.id | WA: 0838-6609-6623
                    </p>
                    <p class="m-0 text-muted" style="margin-top: 4px; font-size: 12px;">
                        <strong>Penerima Pembayaran:</strong> Supriyadi (Bagian Keuangan)
                    </p>
                </td>
                <td style="width: 50%;" class="text-right">
                    <h2 class="invoice-title">INVOICE</h2>
                    <p class="m-0 font-extrabold text-primary" style="font-size: 15px; margin-bottom: 4px;">
                        #INV-{{ str_pad($pembayaran->id, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="m-0 text-dark" style="font-size: 12px; margin-bottom: 12px; font-weight: 500;">Tanggal:
                        {{ $pembayaran->created_at->format('d F Y') }}</p>

                    @php
                        $statusClass = 'badge-pending';
                        if (in_array($pembayaran->status, ['success', 'settlement', 'lunas'])) {
                            $statusClass = 'badge-success';
                        } elseif (in_array($pembayaran->status, ['expire', 'failed', 'cancel'])) {
                            $statusClass = 'badge-failed';
                        }
                    @endphp

                    <span class="badge {{ $statusClass }}">
                        {{ $pembayaran->status == 'success' ? 'LUNAS' : strtoupper($pembayaran->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <!-- BOX INFO (Kiri: Penagihan Kepada, Kanan: Ringkasan) -->
    <table class="layout">
        <tr>
            <!-- Kepada -->
            <td style="width: 48%;">
                <div class="info-box">
                    <div class="section-label">Ditagihkan Kepada:</div>
                    <p class="m-0 font-bold text-dark" style="font-size: 16px;">{{ $tenant->nama_toko }}</p>
                    <p class="m-0 font-semibold" style="margin-top: 4px;">Attn: {{ $tenant->nama_pemilik }}</p>
                    <p class="m-0 text-muted" style="margin-top: 4px;">Email: {{ $tenant->email ?? '-' }}</p>
                    <p class="m-0 text-muted">No. HP: {{ $tenant->no_hp ?? '-' }}</p>
                </div>
            </td>

            <td style="width: 4%;"></td> <!-- Jarak antar kolom -->

            <!-- Ringkasan -->
            <td style="width: 48%;">
                <div class="info-box" style="background: transparent; border: 1px solid #bfdbfe;">
                    <div class="section-label">Ringkasan Pembayaran:</div>
                    <table class="layout" style="margin-top: 6px;">
                        <tr>
                            <td style="padding-bottom: 6px; color: #4b5563; font-size: 12px; font-weight: 500;">Paket
                                Langganan</td>
                            <td class="text-right font-bold" style="font-size: 12px; color: #111827;">
                                {{ $tenant->plan->nama_paket ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="padding-bottom: 6px; color: #4b5563; font-size: 12px; font-weight: 500;">Periode
                            </td>
                            <td class="text-right font-bold" style="font-size: 12px; color: #111827;">1 Bulan</td>
                        </tr>
                        <tr>
                            <td
                                style="padding-top: 8px; border-top: 1px solid #bfdbfe; color: #4b5563; font-weight: 700;">
                                Total Bayar</td>
                            <td class="text-right font-extrabold text-primary"
                                style="padding-top: 8px; border-top: 1px solid #bfdbfe; font-size: 16px;">
                                Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- TABEL ITEM RINCIAN -->
    <table class="items">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 45%;">Deskripsi Layanan</th>
                <th style="width: 15%; text-align: center;">Kuantitas</th>
                <th style="width: 35%; text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold text-center text-dark">1</td>
                <td>
                    <span class="font-bold text-dark" style="font-size: 14px;">Sistem Manajemen Agen Omzetly</span><br>
                    <span style="font-size: 12px; color: #4b5563; margin-top: 4px; display: inline-block;">Langganan
                        Paket: {{ $tenant->plan->nama_paket ?? '-' }} (1 Bulan)</span>
                </td>
                <td class="text-center font-semibold text-dark">1</td>
                <td class="text-right font-bold text-dark">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                </td>
            </tr>

            <!-- Spacer Kosong -->
            <tr>
                <td colspan="4"
                    style="border-top: 1px solid #e5e7eb; border-bottom: none; border-left: none; border-right: none; padding: 10px;">
                </td>
            </tr>

            <!-- Area Kalkulasi Total -->
            <tr class="total-row">
                <td colspan="2" style="border: none;"></td>
                <td class="text-right text-muted font-semibold" style="padding-right: 15px;">Subtotal</td>
                <td class="text-right font-bold text-dark">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                </td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="border: none;"></td>
                <td class="text-right text-muted font-semibold" style="padding-right: 15px;">Pajak (0%)</td>
                <td class="text-right font-bold text-dark">Rp 0</td>
            </tr>
            <tr class="total-row">
                <td colspan="2" style="border: none;"></td>
                <td class="text-right font-extrabold text-dark" style="font-size: 14px; padding-top: 12px;">TOTAL</td>
                <td class="text-right font-extrabold text-primary" style="font-size: 18px; padding-top: 12px;">Rp
                    {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- INFO PAYMENT GATEWAY (Detail Transaksi Teknis) -->
    <div class="payment-meta">
        <h3 class="m-0"
            style="font-size: 13px; font-weight: 800; margin-bottom: 14px; color: #111827; text-transform: uppercase;">
            Riwayat Transaksi Sistem</h3>
        <table class="layout">
            <tr>
                <td style="width: 50%;">
                    <table class="layout">
                        <tr>
                            <td style="width: 130px; font-weight: 600; color: #111827;">Payment Gateway</td>
                            <td class="text-muted">: Midtrans</td>
                        </tr>
                        @if ($pembayaran->payment_channel)
                            <tr>
                                <td style="font-weight: 600; color: #111827;">Metode Bayar</td>
                                <td class="text-muted font-semibold">:
                                    {{ strtoupper(str_replace('_', ' ', $pembayaran->payment_channel)) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td style="font-weight: 600; color: #111827;">Waktu Sistem</td>
                            <td class="text-muted">: {{ $pembayaran->created_at->format('d M Y, H:i:s') }} WIB</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table class="layout">
                        @if ($pembayaran->order_id)
                            <tr>
                                <td style="width: 100px; font-weight: 600; color: #111827;">Order ID</td>
                                <td class="text-muted"
                                    style="font-family: monospace; font-size: 13px; font-weight: 600;">:
                                    {{ $pembayaran->order_id }}</td>
                            </tr>
                        @endif
                        @if ($pembayaran->transaction_id)
                            <tr>
                                <td style="font-weight: 600; color: #111827;">Trx ID</td>
                                <td class="text-muted"
                                    style="font-family: monospace; font-size: 13px; font-weight: 600;">:
                                    {{ $pembayaran->transaction_id }}</td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        <p style="font-size: 11px; color: #4b5563; margin-top: 15px; font-style: italic; font-weight: 500;">
            * Pembayaran diproses secara aman melalui Midtrans (PT Midtrans), partner payment gateway resmi Omzetly.id.
        </p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p style="margin: 0; font-weight: 700; color: #111827; font-size: 13px;">Terima kasih atas pembayaran Anda.</p>
        <p style="margin: 5px 0 0 0; color: #4b5563;">Dokumen ini dihasilkan secara otomatis oleh sistem dan sah tanpa
            tanda tangan fisik.</p>
        <p style="margin: 12px 0 0 0; font-weight: 600;">&copy; {{ date('Y') }} PT Omzetly Digital Indonesia.</p>
    </div>

</body>

</html>
