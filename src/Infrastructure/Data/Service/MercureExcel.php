<?php


namespace App\Infrastructure\Data\Service;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpParser\Builder\Class_;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class MercureExcel
{

    /**
     * @var PublisherInterface
     */
    private PublisherInterface $mercurePublisher;
    public function __construct(PublisherInterface $mercurePublisher)
    {
        $this->mercurePublisher = $mercurePublisher;
    }
    /**
     * @param Spreadsheet $spreadsheet
     * @return int
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function countLines(Spreadsheet $spreadsheet): int
    {
        $linecount = 0;
        for ($i=0; $i < $spreadsheet->getSheetCount(); ++$i) {
            $sheet = $spreadsheet->setActiveSheetIndex($i);
            $linecount += $sheet->getHighestRow()-1;
        }
        return $linecount;
    }
    /**
     * Publish Notfication.
     *
     * @param null $data
     */
    public function publishProgress(string $importId, string $type, $data = null)
    {
        $update = new Update(
            "csv:$importId",
            json_encode(['type' => $type, 'data' => $data]),
        );
        ($this->mercurePublisher)($update);
    }
}