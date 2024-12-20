<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $roles = $token->getRoleNames();

        if (in_array('ROLE_ADMIN', $roles, true)) {
            $redirectUrl = $this->router->generate('app_admin');
        } elseif (in_array('ROLE_TEACHER', $roles, true)) {
            $redirectUrl = $this->router->generate('app_teachers');
        } elseif (in_array('ROLE_STUDENT', $roles, true)) {
            $redirectUrl = $this->router->generate('app_students');
        } else {
            $redirectUrl = $this->router->generate('app_default_redirect');
        }

        return new RedirectResponse($redirectUrl);
    }
}
