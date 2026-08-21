<?php

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
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LabaRugiExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Laba Rugi ' . ($this->data['tanggalAwal'] ?? 'Rekap');
    }

    public function headings(): array
    {
        return ['Cabang', 'Laba Kotor', 'Pengeluaran', 'Laba Bersih', 'Status'];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data['cabangs'] as $cabang) {
            $kotor = $this->data['labaKotor'][$cabang->id] ?? 0;
            $keluar = $this->data['pengeluaran'][$cabang->id] ?? 0;
            $bersih = $this->data['labaBersih'][$cabang->id] ?? 0;

            $rows[] = [$cabang->nama_cabang, $kotor, $keluar, $bersih, $bersih >= 0 ? 'PROFIT' : 'RUGI'];
        }

        $rows[] = ['TOTAL KESELURUHAN', $this->data['totalLabaKotor'], $this->data['totalPengeluaran'], $this->data['totalLabaBersih'], ''];

        return $rows;
    }

    public function columnWidths(): array
    {
        return ['A' => 25, 'B' => 18, 'C' => 18, 'D' => 18, 'E' => 12];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling Header (Navy Blue - Profesional)
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF1E293B']], // Navy Blue
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Format Rupiah Akuntansi (Accounting)
                // Rp di kiri, angka di kanan, merah jika minus
                $rupiahFormat = '_-"Rp"* #,##0_-;[Red]-"Rp"* #,##0_-;_-"Rp"* "-"_-;_-@_-';

                $sheet->getStyle('B2:D' . $highestRow)
                    ->getNumberFormat()->setFormatCode($rupiahFormat);

                $sheet->getStyle('B2:D' . $highestRow)
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Styling Total Row (Baris Paling Bawah)
                $sheet->getStyle('A' . $highestRow . ':E' . $highestRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FF1E40AF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE0F2FE']], // Soft Blue
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF3B82F6']],
                        'bottom' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF3B82F6']],
                    ]
                ]);

                // Menambahkan Border Tipis untuk semua sel data
                $sheet->getStyle('A1:E' . $highestRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            },
        ];
    }
}