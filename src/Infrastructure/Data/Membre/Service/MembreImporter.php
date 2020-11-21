<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Data\Membre\Service;

use App\Infrastructure\Data\Membre\Imports\ProfileImport;
use App\Infrastructure\Data\Membre\Imports\UsersImport;
use Doctrine\ORM\EntityManagerInterface;
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
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var UsersImport
     */
    private UsersImport $usersImport;
    /**
     * @var ProfileImport
     */
    private ProfileImport $profileImport;

    public function __construct(PublisherInterface $mercurePublisher,EntityManagerInterface $entityManager, UsersImport $usersImport ,ProfileImport $profileImport)
    {
        $this->mercurePublisher = $mercurePublisher;
        $this->entityManager = $entityManager;
        $this->usersImport = $usersImport;
        $this->profileImport = $profileImport;
    }

    public function import( int $importId, Spreadsheet $spreadsheet)
    {

        $lineCount = $this->countLines($spreadsheet);
        $batchSize = 50;
        try {
            for($i=0;$i<$spreadsheet->getSheetCount();$i++){
                $spreadsheet->setActiveSheetIndex($i);
                $spreadsheet->getActiveSheet()->removeRow(1);
                $sheetData = $spreadsheet->getActiveSheet()->ToArray(true, true, true);
                foreach ($sheetData as $key=> $sheet){
                    $i==0? $this->entityManager->persist($this->usersImport->model($sheet)):$this->profileImport->model($sheet);
                    if (0 === ($key % $batchSize)) {
                        $this->entityManager->flush();
                        $this->entityManager->clear();
                        $this->publishProgress($importId, 'progress', [
                            'current' => $i,
                            'total' => $lineCount,
                        ]);
                    }
                }
            }
        }catch (\Exception $exception){
            echo  $exception->getMessage();
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
    public function countLines($objPHPExcel): int
    {
        $count = 0;
        for ($i=0; $i < $objPHPExcel->getSheetCount(); ++$i) {
            $sheet = $objPHPExcel->setActiveSheetIndex($i);
            $count += $sheet->getHighestRow()-1;
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
