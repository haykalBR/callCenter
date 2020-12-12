<?php


namespace App\Infrastructure\Data\Traits;


use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait HeadingRowExtractorTrait
{
    /**
     * Set column Names in files.
     *
     * @param $key
     * @param $sheet
     */
    public function columnNames(array $arrays, int $key, Worksheet $sheet): void
    {
        $columnLetter = 'A';
        foreach (array_keys($arrays[0][$key]) as $value) {
            $sheet->setCellValue($columnLetter.'1', $value);
            ++$columnLetter;
        }
    }
    /**
     * @param array $arrays
     * @param Worksheet $sheet
     */
    public function columnSingleNames(array $arrays, Worksheet $sheet): void
    {
        $columnLetter = 'A';
        foreach (array_keys($arrays[0]) as $value) {
            $sheet->setCellValue($columnLetter.'1', $value);
            ++$columnLetter;
        }
    }
}