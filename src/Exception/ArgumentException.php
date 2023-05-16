<?php

namespace Tcepaas\Exception;

class ArgumentException extends ApiException
{
    public function __construct($message, $arg) {
        parent::__construct("{$arg} $message");
    }
}
