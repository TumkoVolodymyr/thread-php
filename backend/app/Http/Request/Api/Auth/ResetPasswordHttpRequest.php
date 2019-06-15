<?php

declare(strict_types = 1);

namespace App\Http\Request\Api\Auth;

use App\Action\Auth\ResetPassword\ResetPasswordRequestInterface;
use App\Http\Request\ApiFormRequest;

final class ResetPasswordHttpRequest extends ApiFormRequest implements ResetPasswordRequestInterface
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
        ];
    }

    public function getEmail(): string
    {
        return $this->get('email');
    }
}
