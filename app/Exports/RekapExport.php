<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RekapExport implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;

    // Variabel pelacakan letak sel
    protected $titleRows = [];
    protected $headerRows = [];
    protected $currencyCells = []; // Menyimpan letak sel (kolom & baris) yang butuh format Rupiah

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Rekap ' . ($this->data['tanggal'] ?? date('Y-m-d'));
    }

    public function array(): array
    {
        $exportData = [];
        $currentRow = 1;

        // 1. KOP LAPORAN
        $exportData[] = ['LAPORAN REKAPITULASI - OMZETLY.ID', '', '', '', ''];
        $this->titleRows[] = $currentRow++;

        $exportData[] = ['Tanggal:', $this->data['tanggal'] ?? '-', '', '', ''];
        $currentRow++;

        $exportData[] = ['Cabang:', $this->data['cabang_terpilih'] ?? 'Semua Cabang', '', '', ''];
        $currentRow++;

        $exportData[] = ['', '', '', '', '']; // Spacer
        $currentRow++;

        // 2. RINGKASAN TRANSAKSI
        $exportData[] = ['RINGKASAN KEUANGAN & TRANSAKSI', '', '', '', ''];
        $this->titleRows[] = $currentRow++;

        $exportData[] = ['Kategori', 'Total / Nilai', '', '', ''];
        $this->headerRows[] = $currentRow++;

        // Kalkulasi Rata-rata
        $totalNonKas = ($this->data['totalTransfer'] ?? 0) + ($this->data['totalTarikTunai'] ?? 0) + ($this->data['totalNumpang'] ?? 0);
        $rataOmzet = $totalNonKas > 0 ? ($this->data['totalOmzet'] ?? 0) / $totalNonKas : 0;

        // Array ke-3 (true/false) digunakan untuk menentukan apakah baris ini nominal Rupiah atau hanya jumlah (count)
        $summaries = [
            ['Total Profit', $this->data['totalOmzet'] ?? 0, true], // Rupiah
            ['Volume Trx (Non-Kas)', $totalNonKas, false], // Bukan Rupiah (Count)
            ['Rata-rata Profit/Trx', round($rataOmzet, 0), true], // Rupiah
            ['Saldo Kas Akhir', $this->data['totalSaldoKas'] ?? 0, true], // Rupiah
            ['Total Semua Transaksi', $this->data['totalTransaksi'] ?? 0, false], // Count
            ['Transfer', $this->data['totalTransfer'] ?? 0, false], // Count
            ['Tarik Tunai', $this->data['totalTarikTunai'] ?? 0, false], // Count
            ['Numpang Transfer', $this->data['totalNumpang'] ?? 0, false], // Count
            ['Penambahan Kas (Setoran)', $this->data['totalPenambahanKas'] ?? 0, true], // Rupiah
            ['Pengurangan Kas (Tarik)', $this->data['totalPenguranganKas'] ?? 0, true], // Rupiah
        ];

        foreach ($summaries as $row) {
            $exportData[] = [$row[0], $row[1], '', '', ''];
            // Jika status Rupiah-nya true, catat lokasi selnya (Misal: Kolom B baris 8)
            if ($row[2]) {
                $this->currencyCells[] = 'B' . $currentRow;
            }
            $currentRow++;
        }
        $exportData[] = ['', '', '', '', ''];
        $currentRow++;

        // 3. REKAP PER BANK
        if (isset($this->data['rekapBank']) && count($this->data['rekapBank']) > 0) {
            $exportData[] = ['REKAPITULASI SALDO PER BANK', '', '', '', ''];
            $this->titleRows[] = $currentRow++;

            $exportData[] = ['Nama Bank/Channel', 'Jumlah Transaksi', 'Total Debit', 'Total Kredit', 'Saldo Akhir'];
            $this->headerRows[] = $currentRow++;

            foreach ($this->data['rekapBank'] as $bank) {
                $exportData[] = [
                    strtoupper($bank['nama']),
                    $bank['total_trx'],
                    $bank['debit'],
                    $bank['kredit'],
                    $bank['saldo']
                ];
                // Debit (C), Kredit (D), dan Saldo (E) adalah nominal Rupiah
                $this->currencyCells[] = 'C' . $currentRow . ':E' . $currentRow;
                $currentRow++;
            }
            $exportData[] = ['', '', '', '', ''];
            $currentRow++;
        }

        // 4. REKAP PER CABANG
        if (isset($this->data['rekapCabang']) && count($this->data['rekapCabang']) > 0) {
            $exportData[] = ['REKAPITULASI KINERJA PER CABANG', '', '', '', ''];
            $this->titleRows[] = $currentRow++;

            $exportData[] = ['Nama Cabang', 'Jumlah Transaksi', 'Total Profit', 'Saldo Kas', ''];
            $this->headerRows[] = $currentRow++;

            foreach ($this->data['rekapCabang'] as $cabang) {
                $exportData[] = [
                    $cabang['nama'],
                    $cabang['total_trx'],
                    $cabang['omzet'],
                    $cabang['saldo_kas'],
                    ''
                ];
                // Profit (C) dan Saldo (D) adalah nominal Rupiah
                $this->currencyCells[] = 'C' . $currentRow . ':D' . $currentRow;
                $currentRow++;
            }
            $exportData[] = ['', '', '', '', ''];
            $currentRow++;
        }

        // 5. REKAP PER OPERATOR / USER
        if (isset($this->data['rekapUser']) && count($this->data['rekapUser']) > 0) {
            $exportData[] = ['REKAPITULASI KINERJA OPERATOR', '', '', '', ''];
            $this->titleRows[] = $currentRow++;

            $exportData[] = ['Nama Operator', 'Cabang', 'Jumlah Transaksi', 'Profit Kontribusi', ''];
            $this->headerRows[] = $currentRow++;

            foreach ($this->data['rekapUser'] as $user) {
                $exportData[] = [
                    $user['nama'],
                    $user['cabang'],
                    $user['total_trx'],
                    $user['omzet'],
                    ''
                ];
                // Profit Kontribusi (D) adalah nominal Rupiah
                $this->currencyCells[] = 'D' . $currentRow;
                $currentRow++;
            }
        }

        return $exportData;
    }

    public function styles(Worksheet $sheet)
    {
        // 1. Style Kop Utama
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A2:A3')->getFont()->setBold(true);

        // 2. Style Judul Bagian
        foreach ($this->titleRows as $row) {
            if ($row > 1) {
                $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
                $sheet->getStyle("A{$row}:E{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FF1E293B');
            }
        }

        // 3. Style Header Kolom
        foreach ($this->headerRows as $row) {
            $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
            $sheet->getStyle("A{$row}:E{$row}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THICK);
            $sheet->getStyle("A{$row}:E{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF8FAFC');
        }

        // 4. FORMAT RUPIAH EXCEL (Accounting Format)
        // Format ini membuat tulisan "Rp" rata kiri, angka rata kanan. 
        // Jika angkanya MINUS, otomatis berubah warna merah dan diberi kurung / minus.
        $rupiahFormat = '_-"Rp"* #,##0_-;[Red]-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-';

        foreach ($this->currencyCells as $cellRange) {
            $sheet->getStyle($cellRange)->getNumberFormat()->setFormatCode($rupiahFormat);
        }

        return [];
    }
}