<?php


namespace App\Infrastructure\Data\Event;


class MailExcelEvent
{
    /**
     * @var array
     */
    private array $date;
    public function __construct(array $data)
    {
        $this->date=$data;
    }
    /**
     * @return array
     */
    public function getDate(){
        return $this->date;
    }
}