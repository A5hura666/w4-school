<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentsController extends AbstractController
{
    #[Route('/student', name: 'students_index')]
    public function index(): Response
    {
        $student = $this->getUser();

        $courses = $student->getCourseEnrollments();
        $allCourses = [];

        foreach ($courses as $courseEnrollment) {
            $course = $courseEnrollment->getCourses();

            $chapters = $course->getChapters();
            $chapterIds = [];
            foreach ($chapters as $chapter) {
                $chapterIds[] = [
                    'id' => $chapter->getId(),
                    'title' => $chapter->getTitle(),
                ];
            }

            $allCourses[] = [
                'id' => $course->getId(),
                'title' => $course->getTitle(),
                'description' => $course->getDescription(),
                'createdAt' => $course->getCreatedAt(),
                'updatedAt' => $course->getUpdatedAt(),
                'teacher' => $course->getTeacherId() ? $course->getTeacherId()->getFirstName() . ' ' . $course->getTeacherId()->getLastName() : 'Inconnu',
                'chapters' => $chapterIds,
            ];
        }

        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentsController',
            'user' => $student,
            'courses' => $allCourses,
        ]);
    }

}
