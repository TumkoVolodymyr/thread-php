<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

use App\Exceptions\InvalidResetCodeException;
use App\Exceptions\ResetCodeExpiredException;
use App\Repository\UserRepository;
use Illuminate\Auth\Passwords\PasswordBroker;

final class CheckResetCodeAction
{
    private const CODE_IS_ALIVE = 10;

    private $userRepository;
    private $passwordBroker;

    public function __construct(
        UserRepository $userRepository,
        PasswordBroker $passwordBroker
    )
    {
        $this->userRepository = $userRepository;
        $this->passwordBroker = $passwordBroker;
    }

    public function execute(CheckResetCodeRequestInterface $request): ChechResetCodeResponseInterface
    {
        $user = $this->userRepository->getByEmail($request->getEmail());

        if ($user->reset_code_created_at->addMinutes(self::CODE_IS_ALIVE) < now()){
            throw new ResetCodeExpiredException();
        }

        if ($user->reset_code !== (int) $request->getCode()){
            throw new InvalidResetCodeException();
        }

        return new ChechResetCodeResponse('Type new password', $this->passwordBroker->createToken($user));
    }
}
