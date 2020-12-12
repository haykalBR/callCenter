<?php


namespace App\Infrastructure\Data\Traits;


use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait SheetTrait
{

    public function getsheetData($spreadsheet,int $i):array
    {
        $sheet = $spreadsheet->getSheet($i);
        $sheet->removeRow(1);
        return $sheet->toArray(null, true, true, true);
    }


}