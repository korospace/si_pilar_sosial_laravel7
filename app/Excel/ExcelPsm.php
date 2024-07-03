<?php

namespace App\Excel;

use App\Excel\Sheets\Psm\PsmSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelPsm implements FromArray, WithMultipleSheets
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
            new PsmSheet($this->data['sheet1'],'Psm'),
        ];

        return $sheets;
    }

}