<?php

namespace App\Controller;

use App\Entity\Courses;
use App\Entity\Media;
use App\Form\CoursesType;
use App\Repository\ChaptersRepository;
use App\Repository\CoursesRepository;
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
        $form = $this->createForm(CoursesType::class, $course);
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
    public function edit($id, Request $request, CoursesRepository $coursesRepository, EntityManagerInterface $em): Response
    {
        $course = $coursesRepository->find($id);

        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }

        $form = $this->createForm(CoursesType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $course->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($course);
            $em->flush();

            return $this->redirectToRoute('teacher_courses_list');
        }

        return $this->render('teacher/courses/edit.html.twig', [
            'form' => $form->createView(),
            'course' => $course,
            'is_dashboard' => true,
        ]);
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
