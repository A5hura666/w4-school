<?php

namespace App\Mail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailService
{
    private MailerInterface $mailer;
    private Environment $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * Envoie un e-mail de bienvenue à un utilisateur.
     */
    public function sendWelcomeEmail(string $to, string $name): void
    {
        $emailContent = $this->twig->render('mail/welcome_email.html.twig', [
            'name' => $name,
        ]);

        $email = (new Email())
            ->from('admin@w4-chool.com')
            ->to($to)
            ->subject('Bienvenue sur notre plateforme')
            ->html($emailContent);

        $this->mailer->send($email);
    }

    /**
     * Envoie un e-mail pour réinitialiser le mot de passe d'un utilisateur.
     */
    public function sendResetPasswordEmail(string $email, string $firstName, string $resetLink): void
    {
        $emailContent = $this->twig->render('mail/reset_password_email.html.twig', [
            'name' => $firstName,
            'resetLink' => $resetLink,
        ]);

        $message = (new Email())
            ->from('admin@w4-chool.com')
            ->to($email)
            ->subject('Réinitialisation de votre mot de passe')
            ->html($emailContent);

        $this->mailer->send($message);
    }
}
