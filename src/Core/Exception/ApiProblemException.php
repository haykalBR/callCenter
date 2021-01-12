<?php


namespace App\Core\Exception;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProblemException extends  HttpException
{

    public function __construct( $statusCode, $message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

}