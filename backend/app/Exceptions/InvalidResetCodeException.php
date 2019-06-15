<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

final class InvalidResetCodeException extends \DomainException
{
    public function __construct($message = "Invalid reset code", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
