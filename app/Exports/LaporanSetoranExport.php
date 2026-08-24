<?php
// app/Exports/LaporanSetoranExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class LaporanSetoranExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Laporan ' . $this->data['tanggal'];
    }

    public function headings(): array
    {
        return ['Keterangan', 'Nominal (Rp)'];
    }

    public function array(): array
    {
        $rows = [
            ['Petugas', $this->data['user']->name ?? '-'],
            ['Cabang', $this->data['user']->cabang->nama_cabang ?? '-'],
            ['Tanggal', \Carbon\Carbon::parse($this->data['tanggal'])->translatedFormat('d F Y')],
            ['', ''],
            ['RINGKASAN KAS', ''],
            ['Saldo Awal Kas', $this->data['saldoAwalKas']],
            ['Tambahan Kas', $this->data['tambahanKas']],
            ['Pengurangan Kas', $this->data['penguranganKas']],
            ['Total Transfer', $this->data['totalTransfer']],
            ['Total Tarik Tunai', $this->data['totalTarikTunai']],
            ['Saldo Akhir Kas', $this->data['saldoAkhirKas']],
            ['', ''],
            ['SALDO BANK', ''],
        ];

        foreach ($this->data['saldoBank'] as $bank => $saldo) {
            if (strtolower($bank) !== 'kas') {
                $rows[] = [ucfirst($bank), $saldo];
            }
        }

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 28,
            'B' => 22,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header tabel (baris 1) - bold + background biru
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);
        $sheet->getStyle('A1:B1')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('2563EB');
        $sheet->getStyle('A1:B1')->getFont()->getColor()->setRGB('FFFFFF');

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Bold baris section header ("RINGKASAN KAS", "SALDO BANK")
                foreach (range(1, $highestRow) as $row) {
                    $value = $sheet->getCell('A' . $row)->getValue();
                    if (in_array($value, ['RINGKASAN KAS', 'SALDO BANK'])) {
                        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
                        $sheet->getStyle('A' . $row . ':B' . $row)->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('EFF6FF');
                    }

                    // Highlight baris "Saldo Akhir Kas"
                    if ($value === 'Saldo Akhir Kas') {
                        $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);
                    }
                }

                // Format angka jadi Rupiah untuk kolom B (baris 6 - highestRow, area nominal)
                $sheet->getStyle('B6:B' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0');

                $sheet->getStyle('B1:B' . $highestRow)
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            },
        ];
    }
}