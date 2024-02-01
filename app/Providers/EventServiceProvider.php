<?php

namespace App\Providers;

use App\Listeners\ContentLengthListener;
use App\Listeners\HandleEntityListener;
use Framework\Dbal\Event\EntityPersist;
use Framework\Event\EventDispatcher;
use Framework\Http\Events\ResponseEvent;
use Framework\Providers\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listen = [
        ResponseEvent::class => [
            ContentLengthListener::class,
        ],
        EntityPersist::class => [
            HandleEntityListener::class,
        ],
    ];

    public function __construct(
        private EventDispatcher $eventDispatcher
    ) {
    }

public function register(): void
{
    foreach ($this->listen as $event => $listeners) {
        foreach (array_unique($listeners) as $listener) {
            $this->eventDispatcher->addListener($event, new $listener);
        }
    }
}
}
