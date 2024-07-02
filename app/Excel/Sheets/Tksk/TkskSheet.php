<?php

namespace App\Excel\Sheets\Tksk;

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

class TkskSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping, WithStrictNullComparison, WithEvents
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
            $row['no_induk_anggota'],
            $row['tempat_tugas'],
            $row['nama'],
            $row['nama_ibu_kandung'],
            $row['nik'],
            $row['tempat_lahir'],
            $row['tanggal_lahir'],
            $row['jenis_kelamin'],
            $row['alamat_jalan'],
            $row['alamat_rt'],
            $row['alamat_rw'],
            $row['alamat_kelurahan'],
            $row['telepon'],
            $row['pendidikan_terakhir'],
            $row['tahun_pengangkatan_pertama'],
            $row['nosk_pengangkatan_pertama'],
            $row['pejabat_pengangkatan_pertama'],
            $row['tahun_pengangkatan_terakhir'],
            $row['nosk_pengangkatan_terakhir'],
            $row['pejabat_pengangkatan_terakhir'],
            $row['nama_di_rekening'],
            $row['no_rekening'],
            $row['nama_bank'],
            $row['no_kartu_registrasi'],
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
                'NOMOR INDUK ANGGOTA',
                'TEMPAT TUGAS (KECAMATAN)',
                'NAMA (SESUAI DENGAN KTP)',
                'NAMA IBU KANDUNG',
                'NIK',
                'TEMPAT LAHIR',
                'TANGGAL LAHIR',
                'JENIS KELAMIN',
                'ALAMAT', // Header utama untuk alamat
                '',       // Kosong untuk RT
                '',       // Kosong untuk RW
                '',       // Kosong untuk kelurahan
                'TELPON',
                'PENDIDIKAN TERKAHIR',
                'PENGANGKATAN TKSK PERTAMA',
                '',       // Kosong untuk no sk
                '',       // Kosong untuk pejabat
                'PENGANGKATAN TKSK TERAKHIR',
                '',       // Kosong untuk no sk
                '',       // Kosong untuk pejabat
                'NAMA DI REKENING',
                'NOMOR REKENING',
                'NAMA BANK',
                'NO KARTU REGISTRASI'
            ],
            [
                '',             // Kosong untuk no urut
                '',             // Kosong untuk tahun
                '',             // Kosong untuk status
                '',             // Kosong untuk wilayah
                '',             // Kosong untuk nomor induk anggota
                '',             // Kosong untuk tempat tugas
                '',             // Kosong untuk nama
                '',             // Kosong untuk nama ibu kandung
                '',             // Kosong untuk NIK
                '',             // Kosong untuk tempat lahir
                '',             // Kosong untuk tanggal lahir
                '',             // Kosong untuk jenis kelamin
                'JALAN',        // Subheader untuk jalan
                'RT',           // Subheader untuk RT
                'RW',           // Subheader untuk RW
                'KELURAHAN',    // Subheader untuk kelurahan
                '',             // Kosong untuk Telpon
                '',             // Kosong untuk Pendidikan Terakhir
                'TAHUN',
                'NOMOR SK',
                'PEJABAT YANG BERTANDA TANGAN',
                'TAHUN',
                'NOMOR SK',
                'PEJABAT YANG BERTANDA TANGAN',
                '',             // Kosong untuk NAMA DI REKENING
                '',             // Kosong untuk NOMOR REKENING
                '',             // Kosong untuk NAMA BANK
                '',             // Kosong untuk NO KARTU REGISTRASI
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
            'I' => '0'
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

                // NO URUT - JENIS KELAMIN
                $sheet->mergeCells('A1:A2'); // merge 1 cell above
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');
                $sheet->mergeCells('G1:G2');
                $sheet->mergeCells('H1:H2');
                $sheet->mergeCells('I1:I2');
                $sheet->mergeCells('J1:J2');
                $sheet->mergeCells('K1:K2');
                $sheet->mergeCells('L1:L2');

                // ALAMAT
                $sheet->mergeCells('M1:P1'); // merge cell

                // TELPON - PENDIDIKAN TERAKHIR
                $sheet->mergeCells('Q1:Q2');
                $sheet->mergeCells('R1:R2');

                // PENGANGKATAN TKSK PERTAMA		
                $sheet->mergeCells('S1:U1'); // merge cell

                // PENGANGKATAN TKSK TERAKHIR
                $sheet->mergeCells('V1:X1'); // merge cell

                // NAMA DI REKENING - NO KARTU REGISTRASI
                $sheet->mergeCells('Y1:Y2');
                $sheet->mergeCells('Z1:Z2');
                $sheet->mergeCells('AA1:AA2');
                $sheet->mergeCells('AB1:AB2');

                // Apply background color and border to header
                $sheet->getStyle('A1:AB2')->applyFromArray([
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