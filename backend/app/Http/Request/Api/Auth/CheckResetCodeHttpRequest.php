<?php

declare(strict_types = 1);

namespace App\Http\Request\Api\Auth;

use App\Action\Auth\ResetPassword\CheckResetCodeRequestInterface;
use App\Http\Request\ApiFormRequest;

final class CheckResetCodeHttpRequest extends ApiFormRequest implements CheckResetCodeRequestInterface
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'code' => 'required|string|min:6'
        ];
    }

    public function getEmail(): string
    {
        return $this->get('email');
    }

    public function getCode(): string
    {
        return $this->get('code');
    }
}
