<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentsController extends AbstractController
{
    #[Route('/students', name: 'app_students')]
    public function index(): Response
    {
        return $this->render('students/index.html.twig', [
            'controller_name' => 'StudentsController',
        ]);
    }
}
