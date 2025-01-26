<?php

namespace App\EventListener;

use App\Entity\Chapters;
use App\Entity\Lessons;
use App\Entity\Sortable;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;

class SortableListener
{
    // Propriété statique pour éviter les triggers multiples
    private static $isUpdating = false;

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Sortable) {
            $em = $args->getObjectManager();
            $repository = $em->getRepository(get_class($entity));
            $this->shiftPositionsPersist($repository, $entity, 1);
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();
        if ($entity instanceof Sortable && $uow->getEntityState($entity) !== UnitOfWork::STATE_REMOVED) {
            $repository = $em->getRepository(get_class($entity));
            $this->shiftPositionsPersist($repository, $entity, -1);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        if (self::$isUpdating || !$entity instanceof Sortable) {
            return;
        }
        $changeSet = $args->getEntityChangeSet();
        if (!isset($changeSet['position'])) {
            return;
        }
        $oldPosition = $changeSet['position'][0];
        $newPosition = $changeSet['position'][1];
        if ($oldPosition === $newPosition) {
            return;
        }
        self::$isUpdating = true;
        $em = $args->getObjectManager();
        $repository = $em->getRepository(get_class($entity));

        if ($newPosition > $oldPosition) {
            $this->shiftPositionsBetween($repository, $entity, $oldPosition + 1, $newPosition, -1);
        } else {
            $this->shiftPositionsBetween($repository, $entity, $newPosition, $oldPosition - 1, 1);
        }

        self::$isUpdating = false;
    }

    public function shiftPositionsBetween($repository, $entity, $start, $end, $shift)
    {
        $qb = $repository->createQueryBuilder('e')
            ->where('e.position BETWEEN :start AND :end');

        // Ajouter une condition supplémentaire selon le type d'entité
        if ($entity instanceof Lessons) {
            $qb->andWhere('e.chapter = :chapter_id')
                ->setParameter('chapter_id', $entity->getChapterId()->getId());
        } elseif ($entity instanceof Chapters) {
            $qb->andWhere('e.course = :course_id')
                ->setParameter('course_id', $entity->getCourseId()->getId());
        }

        $qb->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('e.position', 'ASC');

        $entitiesToUpdate = $qb->getQuery()->getResult();

        foreach ($entitiesToUpdate as $entityToUpdate) {
            $entityToUpdate->setPosition($entityToUpdate->getPosition() + $shift);
            $repository->getEntityManager()->persist($entityToUpdate);
        }

        $repository->getEntityManager()->flush();
    }

    public function shiftPositionsPersist($repository, $entity, $shift)
    {
        self::$isUpdating = true;
        $qb = $repository->createQueryBuilder('e')
            ->where('e.position >= :position');

        if ($entity instanceof Lessons) {
            $qb->andWhere('e.chapter = :chapter_id')
                ->setParameter('chapter_id', $entity->getChapterId()->getId());
        } elseif ($entity instanceof Chapters) {
            $qb->andWhere('e.course = :course_id')
                ->setParameter('course_id', $entity->getCourseId()->getId());
        }

        $qb->setParameter('position', $entity->getPosition())
            ->orderBy('e.position', 'ASC');

        $entitiesToUpdate = $qb->getQuery()->getResult();

        foreach ($entitiesToUpdate as $entityToUpdate) {
            $entityToUpdate->setPosition($entityToUpdate->getPosition() + $shift);
            $repository->getEntityManager()->persist($entityToUpdate);
        }

        $repository->getEntityManager()->flush();
        self::$isUpdating = false;
    }
}
