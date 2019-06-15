<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

final class ResetCodeExpiredException extends \DomainException
{
    public function __construct($message = "Reset code has expired", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
