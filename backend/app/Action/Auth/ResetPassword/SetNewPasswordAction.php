<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

use App\Action\Auth\AuthenticationResponse;
use App\Exceptions\InvalidResetTokenException;
use App\Repository\UserRepository;
use Illuminate\Auth\Passwords\PasswordBroker;

final class SetNewPasswordAction
{
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

    public function execute(SetNewPasswordRequestInterface $request): AuthenticationResponse
    {
        $user = $this->userRepository->getByEmail($request->getEmail());


        if (!$this->passwordBroker->tokenExists($user, $request->getToken())){
            throw new InvalidResetTokenException();
        }

        $this->passwordBroker->deleteToken($user);

        $user->password = $request->getPassword();
        $this->userRepository->save($user);

        $token = auth()->login($user);

        return new AuthenticationResponse(
            $token,
            'bearer',
            auth()->factory()->getTTL() * 60
        );
    }
}
