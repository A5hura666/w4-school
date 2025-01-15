<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Media;
use App\Form\CoursesType;
use App\Form\MediaType;
use App\Repository\ChaptersRepository;
use App\Repository\CoursesRepository;
use App\Repository\MediaRepository;
use App\Service\MediaUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoursesController extends AbstractController
{
    #[Route('/teacher/courses', name: 'teacher_courses_list')]
    public function index(TeachersController $teachersController, CoursesRepository $coursesRepository, ChaptersRepository $chaptersRepository): Response
    {
        $teacher = $teachersController->getUser();
        $courses = $coursesRepository->findBy(['teacher' => $teacher]);
        $chaptersList = [];
        for ($i = 0; $i < count($courses); $i++) {
            $chapters = $chaptersRepository->findBy(['course' => $courses[$i]]);
            $chaptersList[$courses[$i]->getId()] = $chapters;
        }

        return $this->render('teacher/courses/index.html.twig', [
            'controller_name' => 'CoursesController',
            'user' => $teacher,
            'courses' => $courses,
            'chapters' => $chaptersList,
            'is_dashboard' => true,
        ]);
    }

    #[Route('/teacher/courses/create', name: 'courses_create')]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        MediaUploader $mediaUploader,
        TeachersController $teachersController
    ): Response {
        $course = new Courses();
        $form = $this->createForm(CoursesType::class, $course, ['has_illustration' => true, 'media_type' => Media::TYPE_IMAGES]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $teacher = $this->getUser();
            $course->setTeacherId($teacher);
            $course->setCreatedAt(new \DateTimeImmutable());

            $em->beginTransaction();

            try {
                $em->persist($course);
                $em->flush();

                $illustrationFile = $form['illustration']->getData();
                if ($illustrationFile) {
                    $media = $mediaUploader->upload($illustrationFile, $course->getId(), Media::TYPE_IMAGES, Media::RELATED_COURSES);
                    $course->setIllustration($media);
                    $em->persist($media);
                }

                $em->flush();
                $em->commit();

                if ($teachersController->isGranted('ROLE_ADMIN')) {
                    return $this->redirectToRoute('admin_courses');
                }
                return $this->redirectToRoute('teacher_courses_list');
            } catch (\Exception $e) {
                $em->rollback();
                $this->addFlash('error', 'Une erreur est survenue lors de la création du cours ou de l\'upload de l\'illustration.');
                return $this->redirectToRoute('courses_create');
            }
        }

        return $this->render('teacher/courses/create.html.twig', [
            'form' => $form->createView(),
            'is_dashboard' => true,
        ]);
    }

    #[Route("/teacher/courses/{id}/edit", name: "teacher_courses_edit")]
    public function edit(
        $id,
        Request $request,
        CoursesRepository $coursesRepository,
        EntityManagerInterface $em,
        MediaUploader $mediaUploader
    ): Response {
        $course = $coursesRepository->find($id);

        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }

        $courseForm = $this->createForm(CoursesType::class, $course);
        $courseForm->handleRequest($request);

        if ($courseForm->isSubmitted() && $courseForm->isValid()) {
            $course->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($course);
            $em->flush();

            $this->addFlash('success', 'Le cours a été mis à jour avec succès.');

            return $this->redirectToRoute('teacher_courses_list');
        }

        $illustrationForm = $this->createForm(MediaType::class, new Media(), ['media_type' => Media::TYPE_IMAGES]);
        $illustrationForm->handleRequest($request);

        if ($illustrationForm->isSubmitted() && $illustrationForm->isValid()) {
            if ($illustrationForm->has('illustration')) {
                $illustrationFile = $illustrationForm->get('illustration')->getData();

                if ($illustrationFile) {
                    try {
                        if ($oldMedia = $course->getIllustration()) {
                            $mediaUploader->remove($oldMedia);
                        }

                        $media = $mediaUploader->upload(
                            $illustrationFile,
                            $course->getId(),
                            Media::TYPE_IMAGES,
                            Media::RELATED_COURSES
                        );

                        $course->setIllustration($media);
                        $em->persist($media);
                        $em->flush();
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Une erreur est survenue lors de l\'upload de l\'illustration.');
                        return $this->redirectToRoute('teacher_courses_edit', ['id' => $id]);
                    }
                }

                $course->setUpdatedAt(new \DateTimeImmutable());
                $em->persist($course);
                $em->flush();

                $this->addFlash('success', 'L\'illustration a été ajoutée avec succès.');
            }
        }

        return $this->render('teacher/courses/edit.html.twig', [
            'courseForm' => $courseForm->createView(),
            'illustrationForm' => $illustrationForm->createView(),
            'course' => $course,
            'is_dashboard' => true,
            'media' => $course->getIllustration(),
        ]);
    }

    #[Route('/courses/{id}/delete-illustration', name: 'delete_course_illustration', methods: ['POST'])]
    public function deleteIllustration(
        int $id,
        CoursesRepository $coursesRepository,
        EntityManagerInterface $entityManager,
        MediaUploader $mediaUploader
    ): Response {
        $course = $coursesRepository->find($id);

        if (!$course) {
            $this->addFlash('error', 'Le cours demandé est introuvable.');
            return $this->redirectToRoute('teacher_courses_edit', ['id' => $id]);
        }

        $illustration = $course->getIllustration();
        if (!$illustration) {
            $this->addFlash('error', 'Aucune illustration à supprimer.');
            return $this->redirectToRoute('teacher_courses_edit', ['id' => $id]);
        }

        try {
            $mediaUploader->remove($illustration);
            $course->setIllustration(null);
            $entityManager->persist($course);
            $entityManager->flush();

            $this->addFlash('success', 'L\'illustration a été supprimée avec succès.');
        } catch (\RuntimeException $e) {
            $this->addFlash('error', 'Erreur lors de la suppression de l\'illustration : ' . $e->getMessage());
        }

        return $this->redirectToRoute('teacher_courses_edit', ['id' => $id]);
    }

    #[Route("/teacher/courses/{id}/delete", name: "teacher_courses_delete")]
    public function delete(): Response
    {
        return $this->redirectToRoute('teacher_courses_list');
    }

    #[Route("/teacher/courses/{id}/chapters", name: "teacher_courses_chapters")]
    public function chapters($id): Response
    {
        return $this->redirectToRoute('teacher_chapters_list', ['courseId' => $id]);
    }
}
