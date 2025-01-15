<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function countByRole(string $role): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%'.$role.'%')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%'.$role.'%')
            ->getQuery()
            ->getResult();
    }

    // src/Repository/UserRepository.php

    public function countUsersRegisteredLast30Days(): int
    {
        $last30Days = new \DateTime('-30 days');
        $now = new \DateTime();

        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $last30Days)
            ->setParameter('end', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUsersRegisteredPreviousMonth(): int
    {
        $firstDayOfPreviousMonth = new \DateTime('first day of last month');
        $lastDayOfPreviousMonth = new \DateTime('last day of last month 23:59:59');

        return $this->createQueryBuilder('u')
            ->select('count(u.id)')
            ->where('u.createdAt BETWEEN :start AND :end')
            ->setParameter('start', $firstDayOfPreviousMonth)
            ->setParameter('end', $lastDayOfPreviousMonth)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function getUserRegistrationsByMonth(string $filterMode, string $yearSelected ): array
    {
        if ($yearSelected=="null"){
            if ($filterMode == 'month') {
                $currentYear = (new \DateTime())->format('Y');
                $qb = $this->createQueryBuilder('u')
                    ->select('DATE_FORMAT(u.createdAt, \'%Y-%m\') as month, COUNT(u.id) as count')
                    ->where('DATE_FORMAT(u.createdAt, \'%Y\') = :currentYear')
                    ->setParameter('currentYear', $currentYear)
                    ->groupBy('month')
                    ->orderBy('month', 'ASC');
            } else {
                $qb = $this->createQueryBuilder('u')
                    ->select('DATE_FORMAT(u.createdAt, \'%Y\') as year, COUNT(u.id) as count')
                    ->groupBy('year')
                    ->orderBy('year', 'ASC');
            }
        }else{
            $qb = $this->createQueryBuilder('u')
                ->select('DATE_FORMAT(u.createdAt, \'%Y\') as month, COUNT(u.id) as count')
                ->where('DATE_FORMAT(u.createdAt, \'%Y\') = :yearSelected')
                ->setParameter('yearSelected', $yearSelected)
                ->groupBy('month')
                ->orderBy('month', 'ASC');
        }



        return $qb->getQuery()->getResult();
    }



    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
