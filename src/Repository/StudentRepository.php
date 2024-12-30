<?php


namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<User>
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param $student
     * @return courses array
     */
    public function findStudentCourses($student): array
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }
}