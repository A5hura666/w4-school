<?php

namespace App\Event;

use App\Entity\Sortable;

class SortableEvent
{

    public const UPDATE = 'sortable.update';
    public const CREATE = 'sortable.create';
    public const DELETE = 'sortable.delete';

    public function __construct(
        private Sortable $entity,
    ) {
    }

    public function getEntity(): Sortable
    {
        return $this->entity;
    }

}