<?php


namespace Lune\Http\Middleware\Exception;

use Exception;

class UnknownStackException extends Exception
{
    public function __construct($name)
    {
        parent::__construct("Unknown stack: stack '{$name}' does not exist");
    }
}