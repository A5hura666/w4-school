<?php

namespace App\Controller;

use App\Repository\LessonsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LessonsController extends AbstractController
{
    #[Route('/lessons', name: 'app_lessons')]
    public function index(): Response
    {
        return $this->render('lessons/index.html.twig', [
            'controller_name' => 'LessonsController',
        ]);
    }

    #[Route('/lessons/{id}', name: 'app_lessons_show')]
    public function show($id, LessonsRepository $lessonsRepository): Response
    {
        $lesson = $lessonsRepository->findBy(['id' => $id]);
        return $this->render('lessons/show.html.twig', [
            'controller_name' => 'LessonsController',
            'lesson' => $lesson[0],
        ]);
    }

}
