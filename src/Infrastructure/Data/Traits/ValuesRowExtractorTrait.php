<?php


namespace App\Infrastructure\Data\Traits;


use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait ValuesRowExtractorTrait
{
    /**
     * Set Values in files.
     *
     * @param $key
     * @param $sheet
     */
    public function columnValues(array $arrays, int $key, Worksheet $sheet): void
    {
        $i = 2;
        foreach ($arrays as $values) {
            $columnLetter = 'A';
            foreach ($values[$key] as $value) {
                $sheet->setCellValue($columnLetter.$i, $value);
                ++$columnLetter;
            }
            ++$i;
        }
    }
    /**
     * @param array $arrays
     * @param Worksheet $sheet
     */
    public function columnSingleValues(array $arrays, Worksheet $sheet): void
    {
        $i = 2;
        foreach ($arrays as $values) {
            $columnLetter = 'A';
            foreach ($values as $value) {
                $sheet->setCellValue($columnLetter.$i, $value);
                ++$columnLetter;
            }
            ++$i;
        }
    }
}