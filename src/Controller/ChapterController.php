<?php

namespace App\Controller;

use App\Entity\Chapters;
use App\Entity\Courses;
use App\Form\ChaptersType;
use App\Repository\ChaptersRepository;
use App\Repository\LessonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChapterController extends AbstractController
{
    #[Route('/student/chapters', name: 'student_chapters_chapter')]
    public function index(): Response
    {
        return $this->render('student/chapters/index.html.twig', [
            'controller_name' => 'ChapterController',
        ]);
    }

    #[Route('/student/chapters/{id}', name: 'student_chapters_show')]
    public function show($id, ChaptersRepository $chaptersRepository): Response
    {
        $chapter = $chaptersRepository->findBy(['id' => $id]);
        return $this->render('student/chapters/show.html.twig', [
            'controller_name' => 'ChapterController',
            'chapter' => $chapter[0],
        ]);
    }

    #[Route('/teacher/chapters/{courseId}/list', name: 'teacher_chapters_list')]
    public function list(ChaptersRepository $chaptersRepository, LessonsRepository $lessonsRepository, int $courseId): Response
    {
        // Lister les chapitres pour un cours
        $chapters = $chaptersRepository->findBy(['course' => $courseId], ['position' => 'ASC']);

        // Lister les leçons pour chaque chapitre
        $lessonsByChapter = [];
        foreach ($chapters as $chapter) {
            $lessonsByChapter[$chapter->getId()] = $lessonsRepository->findBy(['chapter' => $chapter]);
        }

        return $this->render('teacher/chapters/index.html.twig', [
            'courseId' => $courseId,
            'chapters' => $chapters,
            'lessonsByChapter' => $lessonsByChapter,
            'is_dashboard' => true,
        ]);
    }

    #[Route('/teacher/chapters/{courseId}/create', name: 'teacher_chapters_create')]
    public function create(int $courseId, Request $request, EntityManagerInterface $em, ChaptersRepository $chaptersRepository ): Response
    {
        $chapter = new Chapters();
        $form = $this->createForm(ChaptersType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course = $em->getRepository(Courses::class)->find($courseId);
            if ($course) {
                $chapter->setCourseId($course);
                $chapter->setCreatedAt(new \DateTimeImmutable());
                $chapter->setUpdatedAt(new \DateTimeImmutable());

                // Check for position conflicts
                $position = $chapter->getPosition();
                $conflictingChapters = $chaptersRepository->findChaptersWithPositionGreaterThanOrEqual($position, $courseId);
                foreach ($conflictingChapters as $conflictingChapter) {
                    $conflictingChapter->setPosition($conflictingChapter->getPosition() + 1);
                    $em->persist($conflictingChapter);
                }

                $em->persist($chapter);
                $em->flush();

                return $this->redirectToRoute('teacher_chapters_list', ['courseId' => $courseId]);
            } else {
                throw $this->createNotFoundException('Le cours associé n\'existe pas');
            }
        }

        return $this->render('teacher/chapters/create.html.twig', [
            'form' => $form->createView(),
            'courseId' => $courseId,
            'is_dashboard' => true,
        ]);
    }

    #[Route('/teacher/chapters/{id}/edit', name: 'teacher_chapters_edit')]
    public function edit($id, Request $request, ChaptersRepository $chaptersRepository, EntityManagerInterface $em): Response
    {
        $chapter = $chaptersRepository->find($id);

        if (!$chapter) {
            throw $this->createNotFoundException('Le chapitre n\'existe pas');
        }

        $form = $this->createForm(ChaptersType::class, $chapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chapter->setUpdatedAt(new \DateTimeImmutable());

            // Check for position conflicts
            $position = $chapter->getPosition();
            $courseId = $chapter->getCourseId()->getId();
            $conflictingChapters = $chaptersRepository->findChaptersWithPositionGreaterThanOrEqual($position, $courseId);
            foreach ($conflictingChapters as $conflictingChapter) {
                if ($conflictingChapter->getId() !== $chapter->getId()) {
                    $conflictingChapter->setPosition($conflictingChapter->getPosition() + 1);
                    $em->persist($conflictingChapter);
                }
            }

            $em->persist($chapter);
            $em->flush();

            $course = $chapter->getCourseId();
            if ($course) {
                return $this->redirectToRoute('teacher_chapters_list', ['courseId' => $course->getId()]);
            } else {
                throw $this->createNotFoundException('Le cours associé n\'existe pas');
            }
        }

        return $this->render('teacher/chapters/edit.html.twig', [
            'form' => $form->createView(),
            'chapter' => $chapter,
            'is_dashboard' => true,
        ]);
    }


    #[Route('/teacher/chapters/{id}/delete', name: 'teacher_chapters_delete')]
    public function delete(int $id): Response
    {
        return $this->redirectToRoute('teacher_chapters_list');
    }

    #[Route('/teacher/chapters/{id}/lessons', name: 'teacher_chapters_lessons')]
    public function lessons(int $id): Response
    {
        return $this->redirectToRoute('teacher_lessons_list', ['chapterId' => $id]);
    }
}