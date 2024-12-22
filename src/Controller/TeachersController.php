<?php

namespace App\Controller;

use App\Repository\ChaptersRepository;
use App\Repository\CoursesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeachersController extends AbstractController
{
    #[Route('/teachers', name: 'app_teachers')]
    public function index(TeachersController $teachersController, CoursesRepository $coursesRepository, ChaptersRepository $chaptersRepository): Response
    {
        $teacher = $teachersController->getUser();
        $courses = $coursesRepository->findBy(['teacher' => $teacher]);
        $chaptersList = [];
        for ($i = 0; $i < count($courses); $i++) {
            $chapters = $chaptersRepository->findBy(['course' => $courses[$i]]);
            $chaptersList[$courses[$i]->getId()] = $chapters;
        }

        return $this->render('teachers/dashboard.html.twig', [
            'controller_name' => 'TeachersController',
            'user' => $teacher,
            'courses' => $courses,
            'chapters' => $chaptersList,
        ]);
    }

    #[Route('/teachers/dashboard', name: 'app_teachers_dashboard')]
    public function dashboard(TeachersController $teachersController, CoursesRepository $coursesRepository, ChaptersRepository $chaptersRepository): Response
    {
        $teacher = $teachersController->getUser();
        $courses = $coursesRepository->findBy(['teacher' => $teacher]);
        $chaptersList = [];
        for ($i = 0; $i < count($courses); $i++) {
            $chapters = $chaptersRepository->findBy(['course' => $courses[$i]]);
            $chaptersList[$courses[$i]->getId()] = $chapters;
        }

        return $this->render('teachers/dashboard.html.twig', [
            'controller_name' => 'TeachersController',
            'user' => $teacher,
            'courses' => $courses,
            'chapters' => $chaptersList,
        ]);
    }
}
