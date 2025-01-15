<?php

namespace App\DataFixtures;

use App\Entity\Chapters;
use App\Entity\CourseEnrollments;
use App\Entity\Courses;
use App\Entity\CourseTags;
use App\Entity\Exercises;
use App\Entity\Lessons;
use App\Entity\LessonProgress;
use App\Entity\Notifications;
use App\Entity\Submissions;
use App\Entity\Tags;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        $admin = new User();
        $admin->setEmail('admin@gmail.com')
            ->setLastName('admin')
            ->setFirstName('admin')
            ->setRoles(['ROLE_ADMIN']);
        $admin->setCreatedAt($faker->dateTime());
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        // Données des enseignants
        $teachers = [
            ['email' => 'teacher1@example.com', 'firstName' => 'Teacher1', 'lastName' => 'Test1'],
            ['email' => 'teacher2@example.com', 'firstName' => 'Teacher2', 'lastName' => 'Test2'],
            ['email' => 'teacher3@example.com', 'firstName' => 'Teacher3', 'lastName' => 'Test3'],
            ['email' => 'teacher4@example.com', 'firstName' => 'Teacher4', 'lastName' => 'Test4'],
            ['email' => 'teacher5@example.com', 'firstName' => 'Teacher5', 'lastName' => 'Test5'],
        ];

        $teacherObjects = [];

        foreach ($teachers as $teacherData) {
            $user = new User();
            $user->setEmail($teacherData['email']);
            $user->setFirstName($teacherData['firstName']);
            $user->setLastName($teacherData['lastName']);
            $user->setRoles(['ROLE_TEACHER']);
            $user->setCreatedAt($faker->dateTime());

            // Hash the password
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'teacher');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $teacherObjects[] = $user; // Stocker les enseignants
        }

        // Tags
        $tags = [];
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tags();
            $tag->setName($faker->word());
            $manager->persist($tag);
            $tags[] = $tag;
        }

        // Courses
        $courses = [];
        for ($i = 0; $i < 5; $i++) {
            $course = new Courses();
            $createdAt = \DateTimeImmutable::createFromMutable($faker->dateTime());
            $updatedAt = $faker->optional()->dateTime();
            $updatedAtImmutable = $updatedAt ? \DateTimeImmutable::createFromMutable($updatedAt) : null;

            $course->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph())
                ->setCreatedAt($createdAt)
                ->setUpdatedAt($updatedAtImmutable)
                ->setTeacherId($teacherObjects[array_rand($teacherObjects)]); // Enseignant aléatoire
            $manager->persist($course);
            $courses[] = $course;
        }

        // CoursesTags
        foreach ($courses as $course) {
            $tagCount = rand(2, 5);

            $selectedTags = $faker->randomElements($tags, $tagCount);

            foreach ($selectedTags as $tag) {
                $courseTag = new CourseTags();
                $courseTag->setCourseId($course);
                $courseTag->setTagId($tag);
                $manager->persist($courseTag);
            }
        }

        // Chapters
        $chapters = [];
        foreach ($courses as $course) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                $createdAtChapter = \DateTimeImmutable::createFromMutable($faker->dateTime());
                $updatedAtChapter = $faker->optional()->dateTime();
                $updatedAtChapterImmutable = $updatedAtChapter ? \DateTimeImmutable::createFromMutable($updatedAtChapter) : null;

                $chapter = new Chapters();
                $chapter->setCourseId($course)
                    ->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph())
                    ->setPosition($i + 1)
                    ->setCreatedAt($createdAtChapter)
                    ->setUpdatedAt($updatedAtChapterImmutable);
                $manager->persist($chapter);
                $chapters[] = $chapter;
            }
        }

        // Lessons
        $lessons = [];
        foreach ($chapters as $chapter) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $createdAtLesson = \DateTimeImmutable::createFromMutable($faker->dateTime());
                $updatedAtLesson = $faker->optional()->dateTime();
                $updatedAtLessonImmutable = $updatedAtLesson ? \DateTimeImmutable::createFromMutable($updatedAtLesson) : null;

                $lesson = new Lessons();
                $lesson->setChapterId($chapter)
                    ->setTitle($faker->sentence())
                    ->setContent($faker->paragraph(5))
                    ->setPosition($i + 1)
                    ->setCreatedAt($createdAtLesson)
                    ->setUpdatedAt($updatedAtLessonImmutable);

                $manager->persist($lesson);
                $lessons[] = $lesson;
            }
        }

        $exercises = [];
        // Exercises
        foreach ($lessons as $lesson) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $createdAtExercise = \DateTimeImmutable::createFromMutable($faker->dateTime());
                $updatedAtExercise = $faker->optional()->dateTime();
                $updatedAtExerciseImmutable = $updatedAtExercise ? \DateTimeImmutable::createFromMutable($updatedAtExercise) : null;

                $exercise = new Exercises();
                $exercise->setLessonId($lesson)
                    ->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph())
                    ->setType('quiz')
                    ->setCreatedAt($createdAtExercise)
                    ->setUpdatedAt($updatedAtExerciseImmutable);
                $exercises[] = $exercise;
                $manager->persist($exercise);
            }
        }

//        // Media
//        $mediaList = [];
//        for ($i = 0; $i < 10; $i++) {
//            $media = new Media();
//            $media->setFileName($faker->word() . '.jpg')
//                ->setFilePath($faker->url())
//                ->setMediaType('image')
//                ->setUploadedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
//            $manager->persist($media);
//            $mediaList[] = $media;
//        }

        // Students
        $students = [
            ['email' => 'student1@example.com', 'firstName' => 'Student1', 'lastName' => 'Test1'],
            ['email' => 'student2@example.com', 'firstName' => 'Student2', 'lastName' => 'Test2'],
            ['email' => 'student3@example.com', 'firstName' => 'Student3', 'lastName' => 'Test3'],
            ['email' => 'student4@example.com', 'firstName' => 'Student4', 'lastName' => 'Test4'],
            ['email' => 'student5@example.com', 'firstName' => 'Student5', 'lastName' => 'Test5'],
        ];

        $studentObjects = [];

        foreach ($students as $studentData) {
            $user = new User();
            $user->setEmail($studentData['email']);
            $user->setFirstName($studentData['firstName']);
            $user->setLastName($studentData['lastName']);
            $user->setRoles(['ROLE_STUDENT']);
            $user->setCreatedAt($faker->dateTime());

            // Hash the password
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'student');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $studentObjects[] = $user; // Stocker les étudiants
        }

        // Enrollments
        foreach ($studentObjects as $student) {
            $enrollmentCount = rand(1, 3);
            $selectedCourses = $faker->randomElements($courses, $enrollmentCount);

            foreach ($selectedCourses as $course) {
                $enrollment = new CourseEnrollments();
                $enrollment->setStudent($student)
                    ->setCourses($course)
                    ->setAnrolledAt(new \DateTimeImmutable());
                $manager->persist($enrollment);
            }
        }

        // Lesson Progress
        foreach ($lessons as $lesson) {
            $progress = new LessonProgress();
            $progress->setLessonId($lesson)
                ->setStudent($studentObjects[array_rand($studentObjects)]) // Étudiant aléatoire
                ->setStatus('completed')
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($progress);
        }

        // Notifications
        for ($i = 0; $i < 10; $i++) {
            $notification = new Notifications();
            $notification->setUserId($studentObjects[array_rand($studentObjects)]) // Notification pour un étudiant aléatoire
            ->setMessage($faker->sentence())
                ->setRead($faker->boolean())
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()));
            $manager->persist($notification);
        }

        // Submissions
        foreach ($exercises as $exercice) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $submission = new Submissions();
                $submission->setExercice($exercice)
                    ->setStudent($studentObjects[array_rand($studentObjects)])
                    ->setSubmittedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setScore(rand(0, 100))
                    ->setAnswer($faker->sentence());
                $manager->persist($submission);
            }
        }

        $manager->flush();
    }
}