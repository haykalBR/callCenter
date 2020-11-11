<?php


namespace App\Infrastructure\Data;


interface DataInterface
{
    public function import();
    public function export(array ...$object);
}