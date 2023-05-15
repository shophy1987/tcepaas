<?php

namespace Tcepaas\Exception;

use Tcepaas\ErrorCode;

class ApiException extends \Exception
{
    public function __construct($message, $code = ErrorCode::SystemError, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
