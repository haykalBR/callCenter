<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Data\Traits;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait DataTrait
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
}
