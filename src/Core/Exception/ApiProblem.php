<?php


namespace App\Core\Exception;


class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'There was a validation error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'Invalid JSON format sent';

}