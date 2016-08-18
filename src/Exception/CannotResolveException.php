<?php


namespace Lune\Http\Middleware\Exception;

use Exception;


class CannotResolveException extends Exception
{
    public function __construct($input)
    {
        if (is_object($input)) {
            $str = "instance of " . get_class($input);
        } else if (is_array($input)) {
            $str = "array";
        } else if (is_bool($input)) {
            $str = $input ? "boolean true" : "boolean false";
        } else if (is_null($input)) {
            $str = "null";
        } else {
            $str = (string)$input;
        }


        parent::__construct(sprintf("Unable to resolve %s to MiddlewareInterface", $str));
    }

}