<?php


namespace App\Infrastructure\Data\Membre\Service;


use App\Infrastructure\Data\DataInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MembreImporter implements DataInterface
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    private $spreadsheet;
    private $sheet;
    public function __construct(EntityManagerInterface $entityManager)
        {
            $this->entityManager = $entityManager;
            $this->spreadsheet = new Spreadsheet();
            $this->sheet =  $this->spreadsheet ->getActiveSheet();
        }

    public function import(){}


    public function export(array ...$object)
    {
        $users=$object[0];
        $this->columnNamesUsers($users);
        $this->columnValuesUsers($users);
        $filename = 'Browser_characteristics.csv';
        $contentType = 'text/csv';
        $writer = new Csv($this->spreadsheet);
        $response = new StreamedResponse();
        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename.'"');
        $response->setPrivate();
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCallback(function() use ($writer) {
            $writer->save('php://output');
        });
        return $response;
    }
    private function columnNamesUsers(array $users){
        if(empty($users)){
            //TODO CREATE EXception
        }
        $values= array_keys($users[0]);
        $columnLetter = 'A';
        foreach ($values as $value) {
            $this->sheet->setCellValue($columnLetter.'1', $value);
            $columnLetter++;
        }
    }
    private function columnValuesUsers(array $users){
        $i = 2;
        foreach ($users as $user) {
            $columnLetter = 'A';
            foreach ($user as $value) {
                $this->sheet->setCellValue($columnLetter.$i, $value);
                $columnLetter++;
            }
            $i++;
        }
    }
}