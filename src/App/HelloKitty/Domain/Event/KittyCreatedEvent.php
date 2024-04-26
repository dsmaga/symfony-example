<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Domain\Event;

use App\HelloKitty\Domain\ValueObject\KittyId;
use App\HelloKitty\Domain\ValueObject\Name;
use App\Shared\Application\Event\EventInterface;
use App\Shared\Domain\Event\AbstractEvent;
use App\Shared\Domain\ValueObject\DateTimeImmutableValueObject;
use App\Shared\Domain\ValueObject\Uuid;
use Assert\Assertion;

final class KittyCreatedEvent extends AbstractEvent implements EventInterface
{
    public function __construct(
        KittyId $aggregateId,
        readonly private Name $name,
        readonly private DateTimeImmutableValueObject $createdAt,
        readonly private DateTimeImmutableValueObject $updatedAt,
        ?Uuid $eventId = null,
        ?DateTimeImmutableValueObject $eventCreatedAt = null
    ) {
        parent::__construct($aggregateId, $eventId, $eventCreatedAt);
    }

    /**
     * @return array{name:string, createdAt:string, updatedAt:string}
     */
    public function serialize(): array
    {
        return [
            'name' => (string)$this->name,
            'createdAt' => (string)$this->createdAt,
            'updatedAt' => (string)$this->updatedAt,
        ];
    }

    /**
     * @param array{name:string, createdAt:string, updatedAt:string} $data
     */
    public static function deserialize(
        string $aggregateId,
        array $data,
        string $eventId,
        string $eventCreatedAt
    ): static {
        Assertion::keyExists($data, 'name');
        Assertion::keyExists($data, 'createdAt');
        Assertion::keyExists($data, 'updatedAt');

        return new self(
            new KittyId($aggregateId),
            new Name($data['name']),
            DateTimeImmutableValueObject::create($data['createdAt']),
            DateTimeImmutableValueObject::create($data['updatedAt']),
            new Uuid($eventId),
            DateTimeImmutableValueObject::create($eventCreatedAt)
        );
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function createdAt(): DateTimeImmutableValueObject
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTimeImmutableValueObject
    {
        return $this->updatedAt;
    }
}
