<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordFormType;
use App\Mail\MailService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error) {
            $this->addFlash('error', 'Identifiants incorrects. Veuillez réessayer.');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/register', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, MailService $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);
        $errors = [];

        if ($form->isSubmitted()) {
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            $existingUser = $userRepository->findOneBy(['email' => $email]);
            if ($existingUser) {
                $errors[] = 'Cet email est déjà utilisé.';
            }

            if (!$this->isPasswordValid($password)) {
                $errors[] = 'Le mot de passe est incorrect.';
            }

            if (empty($errors)) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
                $roles = $form->get('roles')->getData();
                if (!is_array($roles)) {
                    $roles = [$roles];
                }

                $user->setFirstName($form->get('first_name')->getData() ?: '');
                $user->setLastName($form->get('last_name')->getData() ?: '');
                $user->setEmail($email);
                $user->setPassword($hashedPassword);
                $user->setRoles($roles);

                $entityManager->persist($user);
                $entityManager->flush();

                $mailer->sendWelcomeEmail($email, $user->getFirstName());

                return $this->redirectToRoute('app_login');
            }
        }

        foreach ($errors as $error) {
            $this->addFlash('error', $error);
        }

        return $this->render('security/register.html.twig', [
            'registration_form' => $form->createView(),
        ]);
    }

    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPasswordPage(): Response
    {
        return $this->render('security/reset_password.html.twig');
    }

    #[Route('/reset-password/send-email', name: 'app_reset_password_send_email', methods: ['POST'])]
    public function sendResetPasswordEmail(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, MailService $mailer): Response
    {
        $email = $request->request->get('email');
        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $this->addFlash('error', 'Aucun utilisateur trouvé avec cet e-mail.');
            return $this->redirectToRoute('app_reset_password');
        }

        $resetToken = bin2hex(random_bytes(32));
        $user->setResetToken($resetToken);
        $entityManager->persist($user);
        $entityManager->flush();

        $resetLink = $this->generateUrl('app_reset_password_change', ['token' => $resetToken], UrlGeneratorInterface::ABSOLUTE_URL);

        $mailer->sendResetPasswordEmail($user->getEmail(), $user->getFirstName(), $resetLink);

        $this->addFlash('success', 'Un e-mail de réinitialisation a été envoyé.');
        return $this->redirectToRoute('app_login');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password_change')]
    public function resetPassword(string $token, Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if (!$user) {
            $this->addFlash('error', 'Le lien de réinitialisation est invalide ou a expiré.');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        $errors = [];

        if ($form->isSubmitted()) {
            $newPassword = $form->get('password')->getData();

            if (!$this->isPasswordValid($newPassword)) {
                $errors[] = 'Le mot de passe est incorrect.';
            }

            if (empty($errors)) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);
                $user->setResetToken(null);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a été changé avec succès.');
                return $this->redirectToRoute('app_login');
            }
        }

        foreach ($errors as $error) {
            $this->addFlash('error', $error);
        }

        return $this->render('security/reset_password_change.html.twig', [
            'reset_password_form' => $form->createView(),
        ]);
    }

    /**
     * Vérifie si un mot de passe respecte les critères.
     */
    private function isPasswordValid(string $password): bool
    {
        return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
    }
}
