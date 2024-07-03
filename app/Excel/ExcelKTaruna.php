<?php

namespace App\Excel;

use App\Excel\Sheets\KarangTaruna\KTarunaSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelKTaruna implements FromArray, WithMultipleSheets
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function sheets(): array
    {
        $sheets = [
            new KTarunaSheet($this->data['sheet1'],'KarangTaruna'),
        ];

        return $sheets;
    }

}