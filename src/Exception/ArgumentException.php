<?php

namespace Tcepaas\Exception;

use Tcepaas\ErrorCode;

class ArgumentException extends ApiException
{
    public function __construct($message, $arg) {
        parent::__construct("{$arg} $message");
    }
}
