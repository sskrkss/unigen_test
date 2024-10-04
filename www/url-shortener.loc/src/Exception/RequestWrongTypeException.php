<?php

namespace App\Exception;

class RequestWrongTypeException extends \RuntimeException
{
    public function __construct(string $attr, string $expectedType)
    {
        parent::__construct("$attr должен быть $expectedType");
    }
}
