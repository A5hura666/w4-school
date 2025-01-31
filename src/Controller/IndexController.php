<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->redirect($this->generateUrl('app_login'));
//        return $this->render('index/index.html.twig', [
//            'controller_name' => 'IndexController',
//        ]);
    }

    #[Route('/redirect', name: 'app_default_redirect')]
    public function defaultRedirect(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
