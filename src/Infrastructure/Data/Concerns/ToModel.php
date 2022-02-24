<?php


namespace App\Infrastructure\Data\Concerns;


interface ToModel
{
    /**
     * @param array $row
     */
    public  function model(array $row);
}