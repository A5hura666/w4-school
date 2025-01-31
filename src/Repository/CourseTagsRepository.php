<?php

namespace App\Repository;

use App\Entity\CourseTags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseTags>
 */
class CourseTagsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseTags::class);
    }

    public function countByTag(int $tagId): int
    {
        $qb = $this->createQueryBuilder('ct')
            ->select('count(ct.id)')
            ->where('ct.id = :tagId')
            ->setParameter('tagId', $tagId);


        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    //    /**
    //     * @return CourseTags[] Returns an array of CourseTags objects
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

    //    public function findOneBySomeField($value): ?CourseTags
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
