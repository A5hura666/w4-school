<?php

namespace App\EventListener;

use App\Entity\Chapters;
use App\Entity\Lessons;
use App\Entity\Sortable;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\UnitOfWork;

class SortableListener
{
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Sortable) {
            $em = $args->getObjectManager();
            $repository = $em->getRepository(get_class($entity));
            $this->shiftPositions($repository, $entity, 1);
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $em = $args->getObjectManager();
        $uow = $em->getUnitOfWork();
        // Check if the entity is being removed in a cascade operation
        if ($entity instanceof Sortable && $uow->getEntityState($entity) !== UnitOfWork::STATE_REMOVED) {
            $repository = $em->getRepository(get_class($entity));
            $this->shiftPositions($repository, $entity, -1);
        }
    }

    private function shiftPositions($repository, $entity, $shift)
    {
        $entities = $repository->createQueryBuilder('e')
            ->where('e.position >= :startPosition')
            ->setParameter('startPosition', $entity->getPosition())
            ->orderBy('e.position', 'ASC');
        if($entity instanceof Chapters) {
            $entities->andWhere('e.course = :course_id')
                ->setParameter('course_id', $entity->getCourseId());
            $entities->getQuery()
                ->getResult();
            $entitiesToUpdate = $entities->getQuery()->getResult();
            for ($i = 0; $i < count($entitiesToUpdate); $i++) {
                $entitiesToUpdate[$i]->setPosition($entitiesToUpdate[$i]->getPosition() + $shift);
            }
        }else{
            $entities->andWhere('e.chapter = :chapter_id')
                ->setParameter('chapter_id', $entity->getChapterId())
                ->getQuery()
                ->getResult();
            $entitiesToUpdate = $entities->getQuery()->getResult();
            for ($i = 0; $i < count($entitiesToUpdate); $i++) {
                $entitiesToUpdate[$i]->setPosition($entitiesToUpdate[$i]->getPosition() + $shift);
            }
        }

    }
}