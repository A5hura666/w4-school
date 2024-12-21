<?php

namespace App\DataFixtures;

use App\Entity\Chapters;
use App\Entity\Courses;
use App\Entity\CourseTags;
use App\Entity\Tags;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use PhpCsFixer\DocBlock\Tag;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $student = new User();
        $student->setEmail('student@gmail.com')
            ->setFirstName('student')
            ->setLastName('student')
            ->setRoles(['ROLE_STUDENT'])
            ->setPassword($this->hasher->hashPassword($student, 'student'));
        $manager->persist($student);

        $teacher = new User();
        $teacher->setEmail('teacher@gmail.com')
            ->setFirstName('teacher')
            ->setLastName('teacher')
            ->setRoles(['ROLE_TEACHER'])
            ->setPassword($this->hasher->hashPassword($teacher, 'teacher'));
        $manager->persist($teacher);

        $admin = new User();
        $admin->setEmail('admin@gmail.com')
            ->setFirstName('admin')
            ->setLastName('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $tagPHP = new Tags();
        $tagPHP->setName('PHP');
        $manager->persist($tagPHP);

        $tagJS = new Tags();
        $tagJS->setName('JavaScript');
        $manager->persist($tagJS);

        $tagPython = new Tags();
        $tagPython->setName('Python');
        $manager->persist($tagPython);

        $tagHTML = new Tags();
        $tagHTML->setName('HTML');
        $manager->persist($tagHTML);

        $tagCSS = new Tags();
        $tagCSS->setName('CSS');
        $manager->persist($tagCSS);

        $course = new Courses();


        $course_tag = new CourseTags();
        $course_tag->

        $chapter = new Chapters();





        $manager->flush();
    }
}
