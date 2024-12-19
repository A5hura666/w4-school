<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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

        $manager->flush();
    }
}
