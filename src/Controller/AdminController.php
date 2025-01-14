<?php

namespace App\Controller;
use App\Entity\Chapters;
use App\Entity\Courses;
use App\Entity\Lessons;
use App\Entity\User;
use App\Form\ChaptersType;
use App\Form\CoursesType;
use App\Form\LessonsType;
use App\Repository\ChaptersRepository;
use App\Repository\CoursesRepository;
use App\Repository\CourseTagsRepository;
use App\Repository\LessonsRepository;
use App\Repository\TagsRepository;
use App\Repository\UserRepository;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/admin', name: 'admin_dashboard')]
    public function index(
        AdminController $adminController,
        UserRepository $userRepository,
        CoursesRepository $coursesRepository,
        TagsRepository $tagsRepository,
        CourseTagsRepository $courseTagsRepository
    )
    : Response
    {
        $admin = $adminController->getUser();
        $userCount = $userRepository->count([]);
        $coursesCount = $coursesRepository->count([]);
        $studentCount = $userRepository->countByRole('ROLE_STUDENT');
        $teacherCount = $userRepository->countByRole('ROLE_TEACHER');
        $adminCount = $userRepository->countByRole('ROLE_ADMIN');
        $coursesTags = $tagsRepository->findAll();
        $tagCounts = [];
        for ($i = 0; $i < count($coursesTags); $i++) {
            $tagCounts[$coursesTags[$i]->getName()] = $courseTagsRepository->countByTag($coursesTags[$i]->getId());
        }

        return $this->render('admin/dashboard.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $admin,
            'is_dashboard' => true,
            'userCount' => $userCount,
            'studentCount' => $studentCount,
            'teacherCount' => $teacherCount,
            'coursesCount' => $coursesCount,
            'tagCounts' => $tagCounts,
            'adminCount' => $adminCount,
            'is_admin' => true,
        ]);
    }

    #[Route('/admin/users/{userType}', name: 'admin_users')]
    public function usersByType(
        $userType,
        UserRepository $userRepository,
        RequestStack $requestStack
    ): Response
    {
        if ($userType == 'teacher') {
            $teachers = $userRepository->findByRole('ROLE_TEACHER');
            return $this->render('admin/user/list.html.twig', [
                'controller_name' => 'AdminController',
                'is_dashboard' => true,
                'is_admin' => true,
                'userType' => $userType,
                'teachers' => $teachers
            ]);
        }elseif ($userType == 'student') {
            $users = $userRepository->findByRole('ROLE_STUDENT');
            return $this->render('admin/user/list.html.twig', [
                'controller_name' => 'AdminController',
                'is_dashboard' => true,
                'is_admin' => true,
                'userType' => $userType,
                'students' => $users
            ]);
        }elseif ($userType == 'admin') {
            $users = $userRepository->findByRole('ROLE_ADMIN');
            return $this->render('admin/user/list.html.twig', [
                'controller_name' => 'AdminController',
                'is_dashboard' => true,
                'is_admin' => true,
                'userType' => $userType,
                'admins' => $users
            ]);
        }else{
            $teachers = $userRepository->findByRole('ROLE_TEACHER');
            $students = $userRepository->findByRole('ROLE_STUDENT');
            $admins = $userRepository->findByRole('ROLE_ADMIN');
            return $this->render('admin/user/list.html.twig', [
                'controller_name' => 'AdminController',
                'is_dashboard' => true,
                'is_admin' => true,
                'admins' => $admins,
                'students' => $students,
                'teachers' => $teachers
            ]);
        }
    }

    #[Route('/admin/allUsers', name: 'admin_allUsers')]
    public function allUsers(
        UserRepository $userRepository
    ): Response
    {
        $admins = $userRepository->findByRole('ROLE_ADMIN');
        $students = $userRepository->findByRole('ROLE_STUDENT');
        $teachers = $userRepository->findByRole('ROLE_TEACHER');

        return $this->render('admin/user/list.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'admins' => $admins,
            'students' => $students,
            'teachers' => $teachers
        ]);
    }



    #[Route('/admin/courses', name: 'admin_courses_list')]
    public function coursesList(
        CoursesRepository $coursesRepository
    ): Response
    {
        $courses = $coursesRepository->findAll();
        return $this->render('admin/courses/list.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'courses' => $courses
        ]);
    }

    #[Route('/admin/courses', name: 'admin_courses')]
    public function courses(
        AdminController $adminController,
        CoursesRepository $coursesRepository
    ){
        $admin = $adminController->getUser();
        $courses = $coursesRepository->findAll();
        return $this->render('admin/courses/index.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $admin,
            'is_dashboard' => true,
            'courses' => $courses,
            'is_admin' => true,

        ]);
    }

    #[Route('/admin/courses/{courseId}/chapters', name: 'admin_chapters_list')]
    public function chapters(
        $courseId,
        AdminController $adminController,
        ChaptersRepository $chaptersRepository
    ): Response
    {
        $admin = $adminController->getUser();
        $chapters = $chaptersRepository->findBy(['course' => $courseId]);
        return $this->render('admin/chapters/list.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $admin,
            'is_dashboard' => true,
            'chapters' => $chapters,
            'is_admin' => true,
            'courseId' => $courseId

        ]);
    }

    #[Route('/admin/courses/create', name: 'admin_courses_create')]
    public function createCourse(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $course = new Courses();
        $form = $this->createForm(CoursesType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('admin_courses');
        }
        return $this->render('admin/courses/create.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/courses/{courseId}/delete', name: 'admin_courses_delete')]
    public function deleteCourse(
        $courseId,
        CoursesRepository $coursesRepository,
        EntityManagerInterface $em
    ){
        $course = $coursesRepository->find($courseId);
        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }
        $em->remove($course);
        $em->flush();
        return $this->redirectToRoute('admin_courses');
    }

    #[Route('/admin/users/{id}/delete', name: 'admin_users_delete')]
    public function deleteUser(
        $id,
        UserRepository $userRepository,
        EntityManagerInterface $em
    ){
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/users/{id}/edit', name: 'admin_users_edit')]
    public function editUser(
        Request $request,
        User $user,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $form->get('roles')->getData();
            $user->setRoles($roles);

            $user->setPassword($user->getPassword());


            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'La modification de l\'utilisateur a été effectuée avec succès');

            return $this->redirectToRoute('admin_users', ['userType' => $user->getRoles()[0]]);
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'is_admin'=> true,
            'is_dashboard' => true,
        ]);
    }

    #[Route('/admin/courses/{courseId}/edit', name: 'admin_courses_edit')]
    public function editCourse(
        $courseId,
        CoursesRepository $coursesRepository,
        EntityManagerInterface $em,
        Request $request
    ){
        $course = $coursesRepository->find($courseId);
        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }
        $form = $this->createForm(CoursesType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($course);
            $em->flush();
            return $this->redirectToRoute('admin_courses');
        }
        return $this->render('admin/courses/edit.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/{id}/detail', name: 'admin_users_detail')]
    public function detailUser(
        $id,
        UserRepository $userRepository
    ){
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }
        return $this->render('admin/user/detail.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'user' => $user,
        ]);
    }


    #[Route('/admin/courses/{id}/detail', name: 'admin_courses_detail')]
    public function detailCourse(
        $id,
        CoursesRepository $coursesRepository
    ){
        $course = $coursesRepository->find($id);
        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }
        return $this->render('admin/courses/detail.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'course' => $course,
        ]);
    }

    #[Route('/admin/courses/{id}/chapters', name: 'admin_courses_chapters')]
    public function chaptersByCourse(
        $id,
        CoursesRepository $coursesRepository
    ){
        $course = $coursesRepository->find($id);
        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }
        return $this->render('admin/chapters/list.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'course' => $course,
        ]);
    }

    #[Route('/admin/courses/{courseId}/chapters/create', name: 'admin_chapters_create')]
    public function createChapter(
        $courseId,
        Request $request,
        EntityManagerInterface $em,
        CoursesRepository $coursesRepository
    ): Response
    {
        $course = $coursesRepository->findBy(['id' => $courseId]);
        $chapter = new Chapters();
        $form = $this->createForm(ChaptersType::class, $chapter);
        $chapter->setCreatedAt(new \DateTimeImmutable());
        $chapter->setUpdatedAt(new \DateTimeImmutable());
        $chapter->setCourseId($course[0]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($chapter);
            $em->flush();
            return $this->redirectToRoute('admin_chapters_list', ['courseId' => $courseId]);
        }
        return $this->render('admin/chapters/create.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'form' => $form->createView(),
            'courseId' => $courseId
        ]);
    }

    #[Route('/admin/courses/{courseId}/chapters/{chapterId}/edit', name: 'admin_chapters_edit')]
    public function editChapter(
        $courseId,
        $chapterId,
        ChaptersRepository $chaptersRepository,
        EntityManagerInterface $em,
        Request $request
    ){
        $chapter = $chaptersRepository->find($chapterId);
        if (!$chapter) {
            throw $this->createNotFoundException('Le chapitre n\'existe pas');
        }
        $form = $this->createForm(ChaptersType::class, $chapter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($chapter);
            $em->flush();
            return $this->redirectToRoute('admin_chapters_list', ['courseId'=>$courseId]);
        }
        return $this->render('admin/chapters/edit.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'form' => $form->createView(),
            'courseId' => $courseId,
            'chapterId' => $chapterId
        ]);
    }

    #[Route('/admin/courses/{courseId}/chapters/{chapterId}/delete', name: 'admin_chapters_delete')]
    public function deleteChapter(
        $courseId,
        $chapterId,
        ChaptersRepository $chaptersRepository,
        EntityManagerInterface $em
    ){
        $chapter = $chaptersRepository->find($chapterId);
        if (!$chapter) {
            throw $this->createNotFoundException('Le chapitre n\'existe pas');
        }
        $em->remove($chapter);
        $em->flush();
        return $this->redirectToRoute('admin_chapters_list', ['courseId' => $courseId]);
    }

    #[Route('admin/courses/{courseId}/chapter/{chapterId}/lessons', name: 'admin_lessons_list')]
    public function lessons(
        $courseId,
        $chapterId,
        AdminController $adminController,
        LessonsRepository $lessonsRepository
    ): Response
    {
        $admin = $adminController->getUser();
        $lessons = $lessonsRepository->findBy(['chapter' => $chapterId]);
        return $this->render('admin/lessons/list.html.twig', [
            'controller_name' => 'AdminController',
            'user' => $admin,
            'is_dashboard' => true,
            'lessons' => $lessons,
            'is_admin' => true,
            'courseId' => $courseId,
            'chapterId' => $chapterId
        ]);
    }

    #[Route('admin/courses/{courseId}/chapter/{chapterId}/lessons/{id}/detail', name: 'admin_lessons_detail')]
    public function detailLesson(
        $id,
        LessonsRepository $lessonsRepository
    )
    {
        $lesson = $lessonsRepository->find($id);
        if (!$lesson) {
            throw $this->createNotFoundException('La leçon n\'existe pas');
        }
        return $this->render('admin/lessons/index.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'lesson' => $lesson,
        ]);
    }

    #[Route('admin/courses/{courseId}/chapter/{chapterId}/lessons/{id}/edit', name: 'admin_lessons_edit')]
    public function editLesson(
        $courseId,
        $chapterId,
        $id,
        LessonsRepository $lessonsRepository,
        EntityManagerInterface $em,
        Request $request,
        ChaptersRepository $chaptersRepository
    ){
        $lesson = $lessonsRepository->find($id);
        if (!$lesson) {
            throw $this->createNotFoundException('La leçon n\'existe pas');
        }
        $form = $this->createForm(LessonsType::class, $lesson);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $chapter = $chaptersRepository->find($chapterId);
            $lesson->setChapterId($chapter);
            $em->persist($lesson);
            $em->flush();
            return $this->redirectToRoute('admin_lessons_list', ['courseId' => $courseId, 'chapterId' => $chapterId]);
        }
        return $this->render('admin/lessons/edit.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'form' => $form->createView(),
            'lesson' => $lesson,
            'courseId' => $courseId,
            'chapterId' => $chapterId
        ]);
    }

    #[Route('admin/courses/{courseId}/chapter/{chapterId}/lessons/create', name: 'admin_lessons_create')]
    public function createLesson(
        $courseId,
        $chapterId,
        Request $request,
        EntityManagerInterface $em,
        ChaptersRepository $chaptersRepository
    ): Response
    {
        $chapter = $chaptersRepository->find($chapterId);
        $lesson = new Lessons();
        $form = $this->createForm(LessonsType::class, $lesson);
        $lesson->setCreatedAt(new \DateTimeImmutable());
        $lesson->setUpdatedAt(new \DateTimeImmutable());
        $lesson->setChapterId($chapter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($lesson);
            $em->flush();
            return $this->redirectToRoute('admin_lessons_list', ['courseId' => $courseId, 'chapterId' => $chapterId]);
        }
        return $this->render('admin/lessons/create.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'form' => $form->createView(),
            'courseId' => $courseId,
            'chapterId' => $chapterId
        ]);
    }

    #[Route('admin/courses/{courseId}/chapter/{chapterId}/lessons/{id}/delete', name: 'admin_lessons_delete')]
    public function deleteLesson(
        $courseId,
        $chapterId,
        $id,
        LessonsRepository $lessonsRepository,
        EntityManagerInterface $em
    ){
        $lesson = $lessonsRepository->find($id);
        if (!$lesson) {
            throw $this->createNotFoundException('La leçon n\'existe pas');
        }
        $em->remove($lesson);
        $em->flush();
        return $this->redirectToRoute('admin_lessons_list', ['courseId' => $courseId, 'chapterId' => $chapterId]);
    }

}
