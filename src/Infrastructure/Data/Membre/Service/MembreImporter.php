<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Data\Membre\Service;

use App\Controller\DefaultController;
use App\Infrastructure\Data\Event\MailExcelEvent;
use App\Infrastructure\Data\Membre\Imports\ProfileImport;
use App\Infrastructure\Data\Membre\Imports\UsersImport;
use App\Infrastructure\Data\Service\MercureExcel;
use App\Infrastructure\Data\Traits\HeadingRowExtractorTrait;
use App\Infrastructure\Data\Traits\ValuesRowExtractorTrait;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Psr\EventDispatcher\EventDispatcherInterface;
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
    use HeadingRowExtractorTrait;
    use ValuesRowExtractorTrait;
    const USERS  ='Users Management';
    const PROFILE='Profile Management';

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
    /**
     * @var MercureExcel
     */
    private MercureExcel $mercureExcel;

    private  EventDispatcherInterface $eventDispatcher;

    public function __construct(MercureExcel $mercureExcel,EntityManagerInterface $entityManager, UsersImport $usersImport ,ProfileImport $profileImport
        ,EventDispatcherInterface $eventDispatcher
    )
    {
        $this->entityManager = $entityManager;
        $this->usersImport = $usersImport;
        $this->profileImport = $profileImport;
        $this->mercureExcel = $mercureExcel;
        $this->eventDispatcher=$eventDispatcher;

    }

    public function import( int $importId, $filePathName)
    {
        //TODO CHNAGE THIS PARTIE AND CREATE BUNDLE  tandhim asemi akthir w les commntatire plus khoudh min bundle deja makhdouma
        ///
        $spreadsheet = IOFactory::load(DefaultController::uploads . $filePathName);
        $lineCount = $this->mercureExcel->countLines($spreadsheet);
        $batchSize = 50;
        $sheetCount=$spreadsheet->getSheetCount();
        $this->mercureExcel->publishProgress($importId, 'message', sprintf('Import of CSV with %d lines started.', $lineCount));
        $lineNumber=0;
        $lineNumberProgress=0;
        $list_users=[];
        $list_profile=[];
       // try {
            for ($i = 0; $i < $sheetCount; $i++) {
                /**
                 * @var  Worksheet $sheet
                 */
                $sheet = $spreadsheet->getSheet($i);
                $sheet->removeRow(1);
                $sheetData = $sheet->toArray(null, true, true, true);
                foreach ($sheetData as $key=> $sheet){
                        $lineNumber++;
                        $lineNumberProgress++;
                        if ($i==0){
                            if (!is_array($this->usersImport->model($sheet))){
                                $this->entityManager->persist($this->usersImport->model($sheet));
                            }else{
                                $list_users[]=$this->usersImport->model($sheet);
                            }
                        }else {
                            if (!is_array($this->profileImport->model($sheet))){
                                $this->entityManager->persist($this->profileImport->model($sheet));
                            }else{
                                $list_profile[]= $this->profileImport->model($sheet);
                            }
                        }
                        if ($lineNumberProgress == $batchSize) {
                            $lineNumberProgress=0;
                            $this->entityManager->flush();
                            $this->entityManager->clear();
                            $this->mercureExcel->publishProgress($importId, 'progress', [
                                'current' => $lineNumber,
                                'total' => $lineCount,
                            ]);
                        }

                    }

                }

            $this->eventDispatcher->dispatch(new MailExcelEvent([]));
            //TODO SERVICE SAVE FILE XITH ARRAY IF EXCIT
            $list=[$list_users,$list_profile];
            foreach ($list as $l){
                return$this->singleExport($l);

            }

        $this->mercureExcel->publishProgress($importId, 'progress', [
                'current' => $lineNumber,
                'total' => $lineCount,
            ]);
            $this->entityManager->flush();
            $this->entityManager->clear();
            $this->mercureExcel->publishProgress($importId, 'message', sprintf('Import of CSV with %d lines finished.', $lineNumber));
      /*  }catch (\Exception $exception){
           $this->publishProgress($importId, 'error', sprintf('Import of CSV has error %s ', $exception->getMessage()));
        }*/

    }
    public function singleExport(array $arrays){
        if (empty($arrays)) {
            throw new EmptyValueException('Ce Array is Empty');
        }
        $spreadsheet =new Spreadsheet();
        $sheet= $spreadsheet->setActiveSheetIndex(0);
        $this->columnSingleNames($arrays, $sheet);
        $this->columnSingleValues($arrays, $sheet);
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
        $writer->save("./Uploads/Export/{$filename}");
        return $response;


    }
    public function export(array $arrays): StreamedResponse
    {

        if (empty($arrays)) {
            throw new EmptyValueException('Ce Array is Empty');
        }
        $namesSheet=[self::USERS, self::PROFILE];
    //    try {
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
      /*  } catch (\Exception $exception) {
            throw  new \Exception($exception->getMessage());
        }*/
    }


}
