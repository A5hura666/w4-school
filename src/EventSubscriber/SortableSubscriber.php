<?php

namespace App\EventSubscriber;

use App\Event\SortableEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SortableSubscriber implements EventSubscriberInterface
{
    public function onSortable($event): void
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SortableEvent::CREATE => 'onSortableCreate',
            SortableEvent::UPDATE => 'onSortableUpdate',
            SortableEvent::DELETE => 'onSortableDelete',
        ];
    }
}
