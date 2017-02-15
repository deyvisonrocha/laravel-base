<?php

namespace App\Exceptions;

class UserCannotSelfRemoveException extends \Exception
{
    public function __construct($message = null)
    {
        parent::__construct($message);
    }
}
