<?php

declare(strict_types = 1);

namespace App\Action\Auth\ResetPassword;

use App\Mail\ResetPasswordEmail;
use App\Repository\UserRepository;
use App\Utils\CodeGenerator;
use Illuminate\Mail\Mailer;

final class ResetAction
{
    private $userRepository;
    private $mailer;

    public function __construct(UserRepository $userRepository, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    public function execute(ResetPasswordRequestInterface $request): ResetPasswordResponseInterface
    {
        $user = $this->userRepository->getByEmail($request->getEmail());

        $code= CodeGenerator::generateNumberCode();

        $user->reset_code = $code;
        $user->reset_code_created_at = now();

        $this->userRepository->save($user);

        $this->mailer->to($user)->send(new ResetPasswordEmail($code));

        return new ResetPasswordResponse('Please confirm reset code');
    }
}
