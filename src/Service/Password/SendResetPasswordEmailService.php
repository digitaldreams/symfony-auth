<?php

namespace App\Service\Password;

use App\Persistence\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class SendResetPasswordEmailService
{
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private UserRepository $userRepository,
       private MailerInterface $mailer
    ) {
    }

    public function execute(string $email)
    {
        $user = $this->userRepository->findOneBy(['email' => $email,]);

        // Marks that you are allowed to see the app_check_email page.

        // Do not reveal whether a user account was found or not.
        if (!$user) {
            return false;

        }

            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
            $email = (new TemplatedEmail())
                ->from(new Address('no-reply@tuhinbepari.com', 'Symfony Auth App'))
                ->to($user->getEmail())
                ->subject('Your password reset request')
                ->htmlTemplate('reset_password/email.html.twig')
                ->context([
                    'resetToken' => $resetToken,
                    'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
                ]);

            $this->mailer->send($email);

            return $resetToken;



    }
}