<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

final class  ResetPasswordResponse implements ResetPasswordResponseInterface
{

    private $message;

    public function __construct(string  $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string{
        return $this->message;
    }
}