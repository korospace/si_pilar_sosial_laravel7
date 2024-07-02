<?php

namespace App\Excel;

use App\Excel\Sheets\Tksk\TkskSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelTksk implements FromArray, WithMultipleSheets
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
            new TkskSheet($this->data['sheet1'],'Tksk'),
        ];

        return $sheets;
    }

}