<?php

namespace App\Excel;

use App\Excel\Sheets\Lks\LksSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelLks implements FromArray, WithMultipleSheets
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
            new LksSheet($this->data['sheet1'],'Lks'),
        ];

        return $sheets;
    }

}