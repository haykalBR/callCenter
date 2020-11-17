<?php


namespace App\Infrastructure\Data\Membre\Service;


use App\Infrastructure\Data\DataInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
class MembreImporter implements DataInterface
{
    Const USERS="Users Management";
    Const PROFILE="Profile Management";
    public function import(){}
    public function export( array $arrays):StreamedResponse
    {
        $namesSheet=[self::USERS,self::PROFILE];
        try {
            $spreadsheet =new Spreadsheet();
            for ($i=0 ;$i<count($arrays[0]);$i++)
            {
                $i!=0?$spreadsheet->createSheet():null;
                $sheet= $spreadsheet ->setActiveSheetIndex($i);
                $spreadsheet->getActiveSheet()->setTitle($namesSheet[$i]);
                $this->columnNames($arrays,$i,$sheet);
                $this->columnValues($arrays,$i,$sheet);
            }
            $spreadsheet ->setActiveSheetIndex(0);
            $filename = "Membre_EXPORT ".gmdate('d-m-y H:i:s').".xlsx";
            $writer = new Xlsx($spreadsheet);
            $response = new StreamedResponse();
            $response->headers->set('Content-Type', "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCallback(function() use ($writer) {
                $writer->save('php://output');
            });
            return $response;
        }catch (\Exception $exception){
                dd($exception->getMessage());
        }

    }
    /**
     * Set column Names in files
     * @param array $users
     * @param $key
     * @param $sheet
     */
    public function columnNames(array $membres,$key,$sheet) :void
    {
        if(empty($users)){
            //TODO CREATE EXception
        }
        $columnLetter = 'A';
         foreach (array_keys($membres[0][$key]) as $v)
           {
              $sheet->setCellValue($columnLetter.'1', $v);
              $columnLetter++;
           }
    }
    /**
     * Set Values in files
     * @param array $users
     * @param $key
     * @param $sheet
     */
    public function columnValues(array $membres,$key,$sheet) :void
    {
           $i = 2;
            foreach ($membres as $values)
            {
                $columnLetter = 'A';
                foreach ($values[$key] as $value){
                    $sheet->setCellValue($columnLetter . $i, $value);
                    $columnLetter++;
                }
             $i++;
        }
    }
}