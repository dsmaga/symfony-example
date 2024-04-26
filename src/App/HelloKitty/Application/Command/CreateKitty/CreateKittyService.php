<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Command\CreateKitty;

use App\HelloKitty\Domain\Event\KittyCreatedEvent;
use App\HelloKitty\Domain\KittyRepositoryInterface;
use App\HelloKitty\Domain\ValueObject\KittyId;
use App\HelloKitty\Domain\ValueObject\Name;
use App\Shared\Domain\ValueObject\DateTimeImmutableValueObject;

final readonly class CreateKittyService
{
    public function __construct(
        private KittyRepositoryInterface $repository
    ) {
    }

    public function create(string $id, string $name): void
    {

        $now = DateTimeImmutableValueObject::create();

        $event = new KittyCreatedEvent(
            new KittyId($id),
            new Name($name),
            $now,
            $now
        );

        $this->repository->save($event);
    }
}
