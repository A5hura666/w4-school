<?php

namespace App\EventListener;

use App\Entity\Sortable;
use Doctrine\Persistence\Event\LifecycleEventArgs;


class SortableListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Sortable) {
            $em = $args->getObjectManager();
            $repository = $em->getRepository(get_class($entity));

            $this->shiftPositions($repository, $entity->getPosition(), 1);
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Sortable) {
            $em = $args->getObjectManager();
            $repository = $em->getRepository(get_class($entity));

            $this->shiftPositions($repository, $entity->getPosition(), -1);
        }
    }

    private function shiftPositions($repository, $startPosition, $shift)
    {
        $entities = $repository->createQueryBuilder('e')
            ->where('e.position >= :startPosition')
            ->setParameter('startPosition', $startPosition)
            ->orderBy('e.position', 'ASC')
            ->getQuery()
            ->getResult();

        foreach ($entities as $entity) {
            $entity->setPosition($entity->getPosition() + $shift);
        }
    }
}