<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\CoursesRepository;
use App\Repository\CourseTagsRepository;
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

    #[Route('/admin/courses/{id}/delete', name: 'admin_courses_delete')]
    public function deleteCourse(
        $id,
        CoursesRepository $coursesRepository,
        EntityManagerInterface $em
    ){
        $course = $coursesRepository->find($id);
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

    public function createUser(
        $id,
        UserRepository $userRepository,
        EntityManagerInterface $em,
    ){
        $user = $userRepository->find($id);
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }
        $form = $this->createForm(UserType::class, $user);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_users');
        }
        return $this->render('admin/user/edit.html.twig', [
            'controller_name' => 'AdminController',
            'is_dashboard' => true,
            'is_admin' => true,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/courses/{id}/edit', name: 'admin_courses_edit')]
    public function editCourse(
        $id,
        CoursesRepository $coursesRepository,
        EntityManagerInterface $em,
        Request $request
    ){
        $course = $coursesRepository->find($id);
        if (!$course) {
            throw $this->createNotFoundException('Le cours n\'existe pas');
        }
        $form = $this->createForm(UserType::class, $course);
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
}
