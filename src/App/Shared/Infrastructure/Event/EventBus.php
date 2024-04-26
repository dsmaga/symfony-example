<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

use App\Shared\Application\Event\EventBusInterface;
use App\Shared\Application\Event\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class EventBus implements EventBusInterface
{
    public function __construct(private MessageBusInterface $eventBus)
    {
    }

    public function publish(EventInterface ...$event): void
    {
        foreach ($event as $e) {
            $this->eventBus->dispatch($e);
        }
    }
}
