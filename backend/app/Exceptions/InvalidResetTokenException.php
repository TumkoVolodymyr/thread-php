<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

final class InvalidResetTokenException extends \DomainException
{
    public function __construct($message = "Invalid reset token", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
