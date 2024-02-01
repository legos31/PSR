<?php

namespace App\Listeners;

use Framework\Dbal\Event\EntityPersist;

class HandleEntityListener
{
    public function __invoke(EntityPersist $event): void
    {
        //dd($event->getEntity());
    }
}
