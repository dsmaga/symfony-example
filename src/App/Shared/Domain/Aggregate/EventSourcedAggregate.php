<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\Aggregate;

use App\Shared\Domain\Event\AbstractEvent;
use App\Shared\Domain\Event\EventStream;
use ReflectionClass;

abstract class EventSourcedAggregate extends Aggregate
{
    abstract public static function hydrate(EventStream $stream): static;

    final protected function apply(AbstractEvent $event): void
    {
        $method = $this->getApplyMethod($event);
        $this->$method($event);
    }

    public function recordApply(AbstractEvent $event): void
    {
        $this->record($event);
        $this->apply($event);
    }

    private function getApplyMethod(AbstractEvent $event): string
    {
        return 'apply' . (new ReflectionClass($event))->getShortName();
    }

    public function reapply(EventStream $stream): void
    {
        foreach ($stream as $event) {
            $this->apply($event);
        }
    }
}
