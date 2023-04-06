<?php

namespace Victorycodedev\MetaapiCloudPhpSdk\Exceptions;

use Exception;

class UnauthorizedException extends Exception
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
