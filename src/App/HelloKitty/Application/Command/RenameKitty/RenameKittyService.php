<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Command\RenameKitty;

use App\HelloKitty\Domain\Event\KittyCreatedEvent;
use App\HelloKitty\Domain\Event\KittyRenamedEvent;
use App\HelloKitty\Domain\Kitty;
use App\HelloKitty\Domain\KittyRepositoryInterface;
use App\HelloKitty\Domain\ValueObject\KittyId;
use App\HelloKitty\Domain\ValueObject\Name;
use App\Shared\Application\Event\EventBusInterface;
use App\Shared\Domain\ValueObject\DateTimeImmutableValueObject;

final readonly class RenameKittyService
{
    public function __construct(
        private KittyRepositoryInterface $repository
    ) {
    }

    public function rename(string $id, string $name): void
    {

        $kittyId = new KittyId($id);

        if (!$this->repository->exists($kittyId)) {
            throw new \RuntimeException('Kitty not found');
        }

        $now = DateTimeImmutableValueObject::create();

        $event = new KittyRenamedEvent(
            $kittyId,
            new Name($name),
            $now
        );

        $this->repository->save($event);
    }
}
