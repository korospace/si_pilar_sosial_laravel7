<?php

namespace App\Excel\Sheets\Psm;

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

class PsmSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping, WithStrictNullComparison, WithEvents
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
            $row['nik'],
            $row['tempat_lahir'],
            $row['tanggal_lahir'],
            $row['jenis_kelamin'],
            $row['tempat_tugas_kelurahan'],
            $row['tempat_tugas_kecamatan'],
            $row['alamat_jalan'],
            $row['alamat_rt'],
            $row['alamat_rw'],
            $row['tingkatan_diklat'],
            $row['sertifikasi_status'],
            $row['sertifikasi_tahun'],
            $row['telepon'],
            $row['pendidikan_terakhir'],
            $row['kondisi_existing'],
        ];
    }

    public function headings(): array
    {
        return [
            [
                'NTR',
                'TAHUN',
                'STATUS',
                'WILAYAH',
                'NAMA PSM',
                'NIK',
                'TEMPAT LAHIR',
                'TANGGAL LAHIR',
                'JENIS KELAMIN',
                'TEMPAT TUGAS',
                '',
                'ALAMAT',
                '',
                '',
                'TINGKATAN DIKLAT',
                'STATUS SERTIFIKASI',
                'TAHUN SERTIFIKASI',
                'NO TELP/ HP',
                'TINGKAT PENDIDIKAN',
                'KONDISI EKSISTING'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'KECAMATAN',
                'KELURAHAN',
                'JALAN',
                'RT',
                'RW',
                '',
                '',
                '',
                '',
                '',
                ''
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
            'A' => '0',
            'F' => '0',
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

                // NTR - JENIS KELAMIN
                $sheet->mergeCells('A1:A2'); // merge 1 cell above
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('I1:I2');

                // TEMPAT TUGAS
                $sheet->mergeCells('J1:K1'); // merge cell

                // ALAMAT
                $sheet->mergeCells('L1:N2');

                // TINGKATAN DIKLAT - KONDISI EKSISTING
                $sheet->mergeCells('O1:O2');
                $sheet->mergeCells('P1:P2');
                $sheet->mergeCells('Q1:Q2');
                $sheet->mergeCells('R1:R2');
                $sheet->mergeCells('S1:S2');
                $sheet->mergeCells('T1:T2');

                // Apply background color and border to header
                $sheet->getStyle('A1:T2')->applyFromArray([
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