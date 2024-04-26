<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Application\Event;

interface EventBusInterface
{
    public function publish(EventInterface ...$event): void;
}
