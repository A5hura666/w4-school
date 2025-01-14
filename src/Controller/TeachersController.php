<?php

namespace App\Controller;

use App\Repository\ChaptersRepository;
use App\Repository\CoursesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeachersController extends AbstractController
{
    #[Route('/teacher', name: 'teacher_dashboard')]
    public function index(TeachersController $teachersController, CoursesRepository $coursesRepository, ChaptersRepository $chaptersRepository): Response
    {
        $teacher = $teachersController->getUser();
        $courses = $coursesRepository->findBy(['teacher' => $teacher]);
        $chaptersList = [];
        for ($i = 0; $i < count($courses); $i++) {
            $chapters = $chaptersRepository->findBy(['course' => $courses[$i]]);
            $chaptersList[$courses[$i]->getId()] = $chapters;
        }

        return $this->render('teacher/index.html.twig', [
            'is_dashboard' => true,
            'controller_name' => 'TeachersController',
            'user' => $teacher,
            'courses' => $courses,
            'chapters' => $chaptersList,
        ]);
    }

//    #[Route('/teacher/courses', name: 'app_teachers_courses')]
//    public function dashboard(TeachersController $teachersController, CoursesRepository $coursesRepository, ChaptersRepository $chaptersRepository): Response
//    {
//        $teacher = $teachersController->getUser();
//        $courses = $coursesRepository->findBy(['teacher' => $teacher]);
//        $chaptersList = [];
//        for ($i = 0; $i < count($courses); $i++) {
//            $chapters = $chaptersRepository->findBy(['course' => $courses[$i]]);
//            $chaptersList[$courses[$i]->getId()] = $chapters;
//        }
//
//        return $this->render('teacher/courses/listOfCourses.html.twig', [
//            'is_dashboard' => true,
//            'controller_name' => 'TeachersController',
//            'user' => $teacher,
//            'courses' => $courses,
//            'chapters' => $chaptersList,
//        ]);
//    }
}
