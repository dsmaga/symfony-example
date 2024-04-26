<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Query\FindKitty;

use App\HelloKitty\Application\Model\KittyDto;
use App\HelloKitty\Domain\Kitty;
use App\HelloKitty\Domain\KittyRepositoryInterface;
use App\HelloKitty\Domain\ValueObject\KittyId;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class FindKittyHandler implements QueryHandlerInterface
{
    public function __construct(
        private KittyRepositoryInterface $repository
    ) {
    }

    public function __invoke(FindKittyQuery $query): ?KittyDto
    {
        $kittyId = new KittyId($query->id);
        $stream = $this->repository->getEventsFor($kittyId);

        if ($stream->count() === 0) {
            return null;
        }

        $kitty = Kitty::hydrate($stream);
        return new KittyDto(
            (string) $kitty->id(),
            (string) $kitty->name(),
            (string) $kitty->createdAt(),
            (string) $kitty->updatedAt()
        );
    }
}
