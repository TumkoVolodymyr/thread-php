<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

final class ChechResetCodeResponse implements ChechResetCodeResponseInterface
{

    private $message;
    private $token;

    public function __construct(string $message, string $token)
    {
        $this->message = $message;
        $this->token = $token;
    }

    public function getMessage(): string {
        return $this->message;
    }

    public function getToken(): string {
        return $this->token;
    }
}