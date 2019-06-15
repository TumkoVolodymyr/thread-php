<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

interface ChechResetCodeResponseInterface
{
    public function getMessage(): string;
    public function getToken(): string;
}