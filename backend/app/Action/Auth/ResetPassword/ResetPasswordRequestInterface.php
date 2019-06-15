<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

interface ResetPasswordRequestInterface
{
    public function getEmail(): string;
}