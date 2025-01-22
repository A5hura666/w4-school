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
            return; // Empêche l'exécution si un update est en cours
        }
        if ($entity instanceof Sortable) {
            // Définir le flag pour éviter que d'autres triggers soient appelés
            self::$isUpdating = true;

            $em = $args->getObjectManager();
            $repository = $em->getRepository(get_class($entity));
            $changeSet = $args->getEntityChangeSet();

            if (isset($changeSet['position'])) {
                if ($changeSet['position'][0] < $changeSet['position'][1]) {
                    $this->shiftPositionsUpdate($repository, $entity, -1, $changeSet['position'][1]);
                } else {
                    $this->shiftPositionsUpdate($repository, $entity, 1, $changeSet['position'][1]);
                }
            }

            // Réinitialiser le flag après la mise à jour
            self::$isUpdating = false;
        }
    }

    public function shiftPositionsUpdate($repository, $entity, $shift, $lastPosition)
    {
        $entityToUpdate = $repository->createQueryBuilder('e')
            ->where('e.position = :endPosition')
            ->andWhere('e.chapter = :chapter_id')
            ->setParameter('endPosition', $lastPosition)
            ->setParameter('chapter_id', $entity->getChapterId()->getId())
            ->orderBy('e.position', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();

        if ($entityToUpdate) {
            $entityToUpdate->setPosition($entityToUpdate->getPosition() + $shift);
            $repository->getEntityManager()->persist($entityToUpdate);
            $repository->getEntityManager()->flush();
        }
    }

    public function shiftPositionsPersist($repository, $entity, $shift){
        self::$isUpdating = true;
        $entitiesToUpdate = [];
        if($entity instanceof Lessons){
            $entitiesToUpdate = $repository->createQueryBuilder('e')
                ->where('e.position >= :position')
                ->andWhere('e.chapter = :chapter_id')
                ->setParameter('position', $entity->getPosition())
                ->setParameter('chapter_id', $entity->getChapterId()->getId())
                ->orderBy('e.position', 'ASC')
                ->getQuery()
                ->getResult();
        }else if ($entity instanceof Chapters) {
            $entitiesToUpdate = $repository->createQueryBuilder('e')
                ->where('e.position >= :position')
                ->andWhere('e.course = :course_id')
                ->setParameter('position', $entity->getPosition())
                ->setParameter('course_id', $entity->getCourseId()->getId())
                ->orderBy('e.position', 'ASC')
                ->getQuery()
                ->getResult();
        }
        foreach ($entitiesToUpdate as $entityToUpdate) {
            $entityToUpdate->setPosition($entityToUpdate->getPosition() + $shift);
            $repository->getEntityManager()->persist($entityToUpdate);
            $repository->getEntityManager()->flush();
        }

        self::$isUpdating = false;

    }

}
