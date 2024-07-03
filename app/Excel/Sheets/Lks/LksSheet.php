<?php

namespace App\Excel\Sheets\Lks;

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

class LksSheet implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping, WithStrictNullComparison, WithEvents
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
            $row['no_telp_yayasan'],
            $row['jenis_layanan'],
            $row['jenis_lks'],
            $row['jumlah_wbs'],
            $row['jumlah_peksos'],
            $row['sk_domisili_yayasan_nomor'],
            $row['sk_domisili_yayasan_masaberlaku_mulai'],
            $row['sk_domisili_yayasan_masaberlaku_selesai'],
            $row['sk_domisili_yayasan_instansi_penerbit'],
            $row['tanda_daftar_yayasan_nomor'],
            $row['tanda_daftar_yayasan_masaberlaku_mulai'],
            $row['tanda_daftar_yayasan_masaberlaku_selesai'],
            $row['tanda_daftar_yayasan_instansi_penerbit'],
            $row['izin_kegiatan_yayasan_nomor'],
            $row['izin_kegiatan_yayasan_masaberlaku_mulai'],
            $row['izin_kegiatan_yayasan_masaberlaku_selesai'],
            $row['izin_kegiatan_yayasan_instansi_penerbit'],
            $row['induk_berusaha_status'],
            $row['induk_berusaha_nomor'],
            $row['induk_berusaha_tgl_terbit'],
            $row['induk_berusaha_instansi_penerbit'],
            $row['akreditasi'],
            $row['akreditasi_tgl'],
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
                'Nama Lembaga',
                'Nama Ketua',
                'Alamat LKS',
                '',
                '',
                '',
                '',
                'No Telp Yayasan',
                'Jenis Layanan (Anak, Napza, Lansia , Disabilitas, Rumah Singgah, Taman Anak Sejahtera, Anak Berhadapan dengan Hukum, Penanganan Korban Bencana)',
                'Jenis LKS (panti, non panti)',
                'Jumlah Warga Binaan Sosial',
                'Jumlah Pekerja Sosial',
                'SURAT KETERANGAN DOMISILI YAYASAN',
                '',
                '',
                '',
                'TANDA DAFTAR YAYASAN',
                '',
                '',
                '',
                'IZIN KEGIATAN YAYASAN',
                '',
                '',
                '',
                'No Induk Berusaha',
                '',
                '',
                '',
                'Akreditasi (Belum , Akreditasi C, Akreditasi B, Akreditasi A)',
                'Tanggal Akreditasi'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                'Jalan',
                'RT',
                'RW',
                'Kelurahan',
                'Kecamatan',
                '',
                '',
                '',
                '',
                '',
                'Nomor',
                'Masa Berlaku - Mulai',
                'Masa Berlaku - Sampai',
                'Instansi Yang Menerbitkan',
                'Nomor',
                'Masa Berlaku - Mulai',
                'Masa Berlaku - Sampai',
                'Instansi Yang Menerbitkan',
                'Nomor',
                'Masa Berlaku - Mulai',
                'Masa Berlaku - Sampai',
                'Instansi Yang Menerbitkan',
                'status (ada/tidak ada)',
                'Nomor',
                'Tanggal Terbit',
                'Instansi Yang Menerbitkan',
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

                // NO URUT - NAMA KETUA
                $sheet->mergeCells('A1:A2'); // merge 1 cell above
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('D1:D2');
                $sheet->mergeCells('E1:E2');
                $sheet->mergeCells('F1:F2');

                // ALAMAT
                $sheet->mergeCells('G1:K1');

                // NO TELP YAYASAN - JUMLAH PEKERJA SOSIAL
                $sheet->mergeCells('L1:L2');
                $sheet->mergeCells('M1:M2');
                $sheet->mergeCells('N1:N2');
                $sheet->mergeCells('O1:O2');
                $sheet->mergeCells('P1:P2');

                // // SURAT KETERANGAN DOMISILI YAYASAN
                $sheet->mergeCells('Q1:T1');

                // // TANDA DAFTAR YAYASAN	
                $sheet->mergeCells('U1:X1');

                // // IZIN KEGIATAN YAYASAN
                $sheet->mergeCells('Y1:AB1');

                // // No Induk Berusaha
                $sheet->mergeCells('AC1:AF1');

                // Akreditasi - Tanggal Akreditasi
                $sheet->mergeCells('AG1:AG2');
                $sheet->mergeCells('AH1:AH2');

                // Apply background color and border to header
                $sheet->getStyle('A1:AH2')->applyFromArray([
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