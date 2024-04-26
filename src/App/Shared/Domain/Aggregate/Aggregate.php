<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Event\AbstractEvent;

class Aggregate
{
    /**
     * @var AbstractEvent[]
     */
    private array $events = [];

    public function record(AbstractEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return AbstractEvent[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->clearRecordedEvents();

        return $events;
    }

    /**
     * @return AbstractEvent[]
     */
    public function getRecordedEvents(): array
    {
        return $this->events;
    }

    public function clearRecordedEvents(): void
    {
        $this->events = [];
    }
}
