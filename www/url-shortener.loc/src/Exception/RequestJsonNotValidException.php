<?php

namespace App\Exception;

class RequestJsonNotValidException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Невалидный json в теле запроса');
    }
}
