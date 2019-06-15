<?php

declare(strict_types = 1);

namespace App\Http\Request\Api\Auth;

use App\Action\Auth\ResetPassword\SetNewPasswordRequestInterface;
use App\Http\Request\ApiFormRequest;

final class SetNewPasswordHttpRequest extends ApiFormRequest implements SetNewPasswordRequestInterface
{

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|string'
        ];
    }

    public function getEmail(): string
    {
        return $this->get('email');
    }

    public function getToken(): string
    {
        return $this->get('token');
    }

    public function getPassword(): string
    {
        return $this->get('password');
    }
}
