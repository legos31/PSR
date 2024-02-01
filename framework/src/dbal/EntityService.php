<?php

namespace Framework\Dbal;

use Doctrine\DBAL\Connection;
use Framework\Dbal\Event\EntityPersist;
use Framework\Event\EventDispatcher;

class EntityService
{
    public function __construct(
        private Connection $connection,
        private EventDispatcher $eventDispatcher
    ) {
    }

public function getConnection(): Connection
{
    return $this->connection;
}

public function save(Entity $entity): int
{
    $entityId = $this->connection->lastInsertId();

    $entity->setId($entityId);

    $this->eventDispatcher->dispatch(new EntityPersist($entity));

    return $entityId;
}
}
