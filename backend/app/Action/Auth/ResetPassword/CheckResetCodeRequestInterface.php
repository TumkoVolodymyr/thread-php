<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

interface CheckResetCodeRequestInterface
{
    public function getEmail(): string;
    public function getCode(): string;
}