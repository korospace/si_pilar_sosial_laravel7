<?php

namespace App\Excel\Sheets\KarangTaruna;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class KTarunaSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping, WithStrictNullComparison, WithEvents
{
    protected $rows;
    protected $newTitle;

    public function __construct(array $rows, string $title)
    {
        $this->rows     = $rows;
        $this->newTitle = $title;
    }

    public function map($row): array
    {
        return [
            $row['no_urut'],
            $row['year'],
            $row['status'],
            $row['wilayah'],
            $row['nama'],
            $row['nama_ketua'],
            $row['alamat_jalan'],
            $row['alamat_rt'],
            $row['alamat_rw'],
            $row['alamat_kelurahan'],
            $row['alamat_kecamatan'],
            $row['telepon'],
            $row['kepengurusan_status'],
            $row['kepengurusan_sk_tgl'],
            $row['kepengurusan_periode_tahun'],
            $row['kepengurusan_jumlah'],
            $row['kepengurusan_pejabat'],
            $row['keaktifan_status'],
            $row['program_unggulan'],
        ];
    }

    public function headings(): array
    {
        return [
            [
                'NO URUT',
                'TAHUN',
                'STATUS',
                'WILAYAH',
                'NAMA LEMBAGA',
                'NAMA KETUA',
                'ALAMAT',
                '',
                '',
                '',
                '',
                'NO HP',
                'STATUS KEPENGURUSAN (sudah terbentuk, belum terbentuk)',
                'KEPENGURUSAN',
                '',
                '',
                '',
                'KEAKTIFAN (tidak aktif, kurang aktif, aktif, sangat aktif)',
                'PROGRAM UNGULAN',
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                'JALAN',
                'RT',
                'RW',
                'KELURAHAN',
                'KECAMATAN',
                '',
                '',
                'TANGGAL SK',
                'PERIODE TAHUN',
                'JUMLAH PENGURUS',
                'PEJABAT',
                '',
                '',
            ]
        ];
    }

    public function array(): array
    {
        return $this->rows;
    }

    public function title(): string
    {
        return $this->newTitle;
    }

    public function columnFormats(): array
    {
        return [
        ];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                /* Set the height of row  */
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // NTR - NAMA KETUA
                $sheet->mergeCells('A1:A2'); // merge 1 cell above
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');

                // ALAMAT
                $sheet->mergeCells('G1:K1');

                // NO HP - STATUS KEPENGURUSAN
                $sheet->mergeCells('L1:L2');
                $sheet->mergeCells('M1:M2');

                // KEPENGURUSAN
                $sheet->mergeCells('N1:Q1');

                // KEAKTIFAN - PROGRAM UNGGULAN
                $sheet->mergeCells('R1:R2');
                $sheet->mergeCells('S1:S2');

                // Apply background color and border to header
                $sheet->getStyle('A1:S2')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => 'D3D3D3'] // Light grey background
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'bold' => true,
                    ],
                ]);
            },
        ];
    }
}