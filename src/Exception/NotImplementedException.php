<?php

namespace Tcepaas\Exception;

use Tcepaas\ErrorCode;

class NotImplementedException extends ApiException
{
    public function __construct() {
        parent::__construct('not implemented');
    }
}
