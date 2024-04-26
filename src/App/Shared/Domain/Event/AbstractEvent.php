<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\Event;

use App\Shared\Domain\ValueObject\DateTimeImmutableValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use DateTimeImmutable;
use ReflectionClass;

abstract class AbstractEvent
{
    private Uuid $eventId;
    private DateTimeImmutableValueObject $eventCreatedAt;

    public function __construct(
        private readonly Uuid $aggregateId,
        ?Uuid $eventId = null,
        ?DateTimeImmutableValueObject $eventCreatedAt = null
    ) {
        $this->eventId = $eventId ?? Uuid::create();
        $this->eventCreatedAt = $eventCreatedAt ?? new DateTimeImmutableValueObject(new DateTimeImmutable());
    }

    /**
     * @param array<string, mixed> $data
     */
    abstract public static function deserialize(
        string $aggregateId,
        array $data,
        string $eventId,
        string $eventCreatedAt
    ): static;

    /**
     * @return array<string, mixed>
     */
    abstract public function serialize(): array;

    public function eventName(): string
    {
        return (new ReflectionClass(static::class))->getShortName();
    }

    final public function aggregateId(): Uuid
    {
        return $this->aggregateId;
    }

    final public function eventId(): Uuid
    {
        return $this->eventId;
    }

    final public function eventCreatedAt(): DateTimeImmutableValueObject
    {
        return $this->eventCreatedAt;
    }
}
