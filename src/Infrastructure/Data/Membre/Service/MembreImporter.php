<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Data\Membre\Service;

use Symfony\Component\Mercure\Update;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Infrastructure\Data\DataInterface;
use App\Core\Exception\EmptyValueException;
use App\Infrastructure\Data\Traits\DataTrait;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MembreImporter implements DataInterface
{
    use DataTrait;
    const USERS  ='Users Management';
    const PROFILE='Profile Management';

    private PublisherInterface $mercurePublisher;

    public function __construct(PublisherInterface $mercurePublisher)
    {
        $this->mercurePublisher = $mercurePublisher;
    }

    public function import($importId, $objPHPExcel)
    {
        $lineCount = $this->countLines($objPHPExcel);
        $batchSize = 100;
        $sheet     = $objPHPExcel->setActiveSheetIndex(0);
        for ($i = 2; $i <= $lineCount; ++$i) {
            $id                       =(int) $sheet->getCell('A'.$i)->getValue();
            $username                 =$sheet->getCell('B'.$i)->getValue();
            $email                    =$sheet->getCell('C'.$i)->getValue();
            $enabled                  =$sheet->getCell('D'.$i)->getValue();
            $createdAt                =$sheet->getCell('E'.$i)->getValue();
            $updatedAt                =$sheet->getCell('F'.$i)->getValue();
            $deletedAt                =$sheet->getCell('G'.$i)->getValue();
            $googleAuthenticatorSecret=$sheet->getCell('H'.$i)->getValue();
            if (0 === ($i % $batchSize)) {
                $this->publishProgress($importId, 'progress', [
                    'current' => $i,
                    'total'   => $lineCount,
                ]);
            }
        }
        $this->publishProgress($importId, 'message', sprintf('Import of CSV with %d lines finished.', $lineCount));
    }

    public function export(array $arrays): StreamedResponse
    {
        if (empty($arrays)) {
            throw new EmptyValueException('Ce Array is Empty');
        }
        $namesSheet=[self::USERS, self::PROFILE];
        try {
            $spreadsheet =new Spreadsheet();
            for ($i=0; $i < \count($arrays[0]); ++$i) {
                0 !== $i ? $spreadsheet->createSheet() : null;
                $sheet= $spreadsheet->setActiveSheetIndex($i);
                $spreadsheet->getActiveSheet()->setTitle($namesSheet[$i]);
                $this->columnNames($arrays, $i, $sheet);
                $this->columnValues($arrays, $i, $sheet);
            }
            $spreadsheet->setActiveSheetIndex(0);
            $filename = 'Membre_EXPORT '.gmdate('d-m-y H:i:s').'.xlsx';
            $writer   = new Xlsx($spreadsheet);
            $response = new StreamedResponse();
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function () use ($writer) {
                $writer->save('php://output');
            });
             //TODO Folde ands test if droit d'access and direct mawjoud
            $writer->save("./Export/{$filename}");
            return $response;
        } catch (\Exception $exception) {
            throw  new \Exception($exception->getMessage());
        }
    }

    /**
     * get count of line.
     *
     * @param $objPHPExcel
     */
    private function countLines($objPHPExcel): int
    {
        $count = 0;
        for ($i=0; $i < $objPHPExcel->getSheetCount(); ++$i) {
            $sheet = $objPHPExcel->setActiveSheetIndex($i);
            $count += $sheet->getHighestRow();
        }

        return $count;
    }

    /**
     * Publish Notfication.
     *
     * @param null $data
     */
    private function publishProgress(string $importId, string $type, $data = null)
    {
        $update = new Update(
            "csv:$importId",
            json_encode(['type' => $type, 'data' => $data]),
        );

        ($this->mercurePublisher)($update);
    }
}
