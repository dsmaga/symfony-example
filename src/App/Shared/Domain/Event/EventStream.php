<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use App\Shared\Domain\ValueObject\Uuid;
use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;

/**
 * @template-implements IteratorAggregate<int, AbstractEvent>
 */
readonly class EventStream implements IteratorAggregate, Countable
{
    /**
     * @param AbstractEvent[] $events
     */
    public function __construct(
        public Uuid $aggregateId,
        private array $events
    ) {
    }

    /**
     * @return ArrayIterator<int, AbstractEvent>
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }
}
