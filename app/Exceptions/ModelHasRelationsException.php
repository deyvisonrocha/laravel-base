<?php

namespace App\Exceptions;

class ModelHasRelationsException extends \Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message);
    }
}
