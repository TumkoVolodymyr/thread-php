<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

interface ResetPasswordResponseInterface
{
    public function getMessage(): string;
}