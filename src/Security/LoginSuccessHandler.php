<?php

// src/Security/LoginSuccessHandler.php
namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Routing\RouterInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $roles = $token->getRoles();
        foreach ($roles as $role) {
            if (in_array('ROLE_ADMIN', $role->getRole())) {
                return new RedirectResponse($this->router->generate('admin_homepage')); // Page d'accueil de l'admin
            } elseif (in_array('ROLE_TEACHER', $role->getRole())) {
                return new RedirectResponse($this->router->generate('teachers_homepage')); // Page d'accueil des professeurs
            } elseif (in_array('ROLE_STUDENT', $role->getRole())) {
                return new RedirectResponse($this->router->generate('students_homepage')); // Page d'accueil des étudiants
            }
        }

        return new RedirectResponse($this->router->generate('app_homepage')); // Page d'accueil par défaut
    }
}
