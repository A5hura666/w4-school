<?php

namespace App\Repository;

use App\Entity\Courses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Courses>
 */
class CoursesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courses::class);
    }

    public function countUniqueStudents($teacher): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(DISTINCT s.id)')
            ->join('c.teacher', 't')
            ->join('c.courseEnrollments', 'ce')
            ->join('ce.student', 's')
            ->where('c.teacher = :teacher')
            ->setParameter('teacher', $teacher)
            ->getQuery()
            ->getSingleScalarResult();
    }

    

    //    /**
    //     * @return Courses[] Returns an array of Courses objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Courses
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
