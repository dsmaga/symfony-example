<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Domain;

use App\HelloKitty\Domain\Event\KittyCreatedEvent;
use App\HelloKitty\Domain\Event\KittyRenamedEvent;
use App\HelloKitty\Domain\ValueObject\KittyId;
use App\HelloKitty\Domain\ValueObject\Name;
use App\Shared\Domain\Aggregate\EventSourcedAggregate;
use App\Shared\Domain\Event\EventStream;
use App\Shared\Domain\ValueObject\DateTimeImmutableValueObject;
use Assert\Assertion;

final class Kitty extends EventSourcedAggregate
{
    private KittyId $id;

    private Name $name;
    private DateTimeImmutableValueObject $createdAt;

    private DateTimeImmutableValueObject $updatedAt;


    public function id(): KittyId
    {
        return $this->id;
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

    public function setId(KittyId $id): void
    {
        $this->id = $id;
    }

    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    public function setCreatedAt(DateTimeImmutableValueObject $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTimeImmutableValueObject $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }


    public function applyKittyCreatedEvent(KittyCreatedEvent $event): void
    {
        $this->setId(new KittyId((string)$event->aggregateId()));
        $this->setName($event->name());
        $this->setCreatedAt($event->createdAt());
        $this->setUpdatedAt($event->updatedAt());
    }

    public function applyKittyRenamedEvent(KittyRenamedEvent $event): void
    {
        Assertion::eq($event->aggregateId(), $this->id);

        $this->setName($event->name());
        $this->setUpdatedAt($event->updatedAt());
    }

    /**
     * @return Kitty
     */
    public static function hydrate(EventStream $stream): static
    {
        $kitty = new self();
        $kitty->reapply($stream);
        return $kitty;
    }
}
