<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/teacher/exercises', name: 'teacher_exercises_')]
class ExercisesController extends AbstractController
{
    #[Route('/{lessonId}/list', name: 'list')]
    public function list(int $lessonId): Response
    {
        return $this->render('teacher/exercises/index.html.twig', ['lessonId' => $lessonId]);
    }

    #[Route('/{lessonId}/create', name: 'create')]
    public function create(int $lessonId): Response
    {
        return $this->render('teacher/exercises/create.html.twig', ['lessonId' => $lessonId]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(int $id): Response
    {
        return $this->render('teacher/exercises/edit.html.twig', ['id' => $id]);
    }

    #[Route('/{id}/delete', name: 'delete')]
    public function delete(int $id): Response
    {
        return $this->redirectToRoute('teacher_exercises_list');
    }
}
