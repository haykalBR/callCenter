<?php


namespace App\Infrastructure\Data;


use Symfony\Component\HttpFoundation\StreamedResponse;

interface DataInterface
{
    public function import();
    public function export(array  $object):StreamedResponse;
    public function columnNames(array $object,string $key,$sheet):void ;
    public function columnValues(array $object,string $key,$sheet):void ;

}