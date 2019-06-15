<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

interface SetNewPasswordRequestInterface
{
    public function getEmail(): string;
    public function getToken(): string;
    public function getPassword(): string;
}