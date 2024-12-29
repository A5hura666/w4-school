<?php

namespace App\Controller;

use App\Entity\Chapters;
use App\Entity\Lessons;
use App\Form\LessonsType;
use App\Repository\LessonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LessonsController extends AbstractController
{
    // STUDENT
    #[Route('/student/lessons/{id}', name: 'student_lessons_show')]
    public function show($id, LessonsRepository $lessonsRepository): Response
    {
        $lesson = $lessonsRepository->findBy(['id' => $id]);
        if (!$lesson) {
            throw $this->createNotFoundException('La leçon n\'existe pas');
        }

        return $this->render('student/lessons/show.html.twig', [
            'controller_name' => 'LessonsController',
            'lesson' => $lesson[0],
        ]);
    }

    // TEACHER
    #[Route('/teacher/lessons/{chapterId}/list', name: 'teacher_lessons_list')]
    public function list(int $chapterId): Response
    {
        return $this->render('teacher/lessons/index.html.twig', ['chapterId' => $chapterId]);
    }

    #[Route('/teacher/lessons/{chapterId}/create', name: 'teacher_lessons_create')]
    public function create(int $chapterId, Request $request, EntityManagerInterface $em): Response
    {
        $lesson = new Lessons();
        $form = $this->createForm(LessonsType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chapter = $em->getRepository(Chapters::class)->find($chapterId);
            if ($chapter) {
                $lesson->setChapterId($chapter);
                $lesson->setCreatedAt(new \DateTimeImmutable());
                $em->persist($lesson);
                $em->flush();
                $courseId = $chapter->getCourseId()->getId();

                return $this->redirectToRoute('teacher_chapters_list', ['courseId' => $courseId]);
            } else {
                throw $this->createNotFoundException('Le chapitre associé n\'existe pas');
            }
        }

        return $this->render('teacher/lessons/create.html.twig', [
            'form' => $form->createView(),
            'chapterId' => $chapterId,
            'is_dashboard' => true,
        ]);
    }

    #[Route('/teacher/lessons/{id}/edit', name: 'teacher_lessons_edit')]
    public function edit(int $id, Request $request, LessonsRepository $lessonsRepository, EntityManagerInterface $em): Response
    {
        $lesson = $lessonsRepository->find($id);

        if (!$lesson) {
            throw $this->createNotFoundException('La leçon n\'existe pas');
        }

        $form = $this->createForm(LessonsType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lesson->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($lesson);
            $em->flush();

            $chapter = $lesson->getChapterId();
            if ($chapter) {
                $courseId = $chapter->getCourseId()->getId();

                return $this->redirectToRoute('teacher_chapters_list', ['courseId' => $courseId]);
            } else {
                throw $this->createNotFoundException('Le chapitre associé n\'existe pas');
            }
        }

        return $this->render('teacher/lessons/edit.html.twig', [
            'form' => $form->createView(),
            'lesson' => $lesson,
            'is_dashboard' => true,
        ]);
    }

    #[Route('/teacher/lessons/{id}/delete', name: 'teacher_lessons_delete')]
    public function delete(int $id): Response
    {
        return $this->redirectToRoute('teacher_lessons_list');
    }

    #[Route('/teacher/lessons/{id}/exercises', name: 'teacher_lessons_exercises')]
    public function exercises(int $id): Response
    {
        return $this->redirectToRoute('teacher_exercises_list', ['lessonId' => $id]);
    }
}
