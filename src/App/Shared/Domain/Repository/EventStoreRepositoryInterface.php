<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\Repository;

use App\Shared\Domain\Event\AbstractEvent;
use App\Shared\Domain\Event\EventStream;
use App\Shared\Domain\ValueObject\Uuid;

interface EventStoreRepositoryInterface
{
    public function getEventsFor(Uuid $aggregateId): EventStream;

    public function save(AbstractEvent $event): void;
}
