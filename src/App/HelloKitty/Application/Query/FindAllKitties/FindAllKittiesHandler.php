<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Query\FindAllKitties;

use App\HelloKitty\Application\Model\KittyCollectionDto;
use App\HelloKitty\Application\Model\KittyDto;
use App\HelloKitty\Domain\Kitty;
use App\HelloKitty\Domain\KittyRepositoryInterface;
use App\HelloKitty\Domain\ValueObject\KittyId;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class FindAllKittiesHandler implements QueryHandlerInterface
{
    public function __construct(
        private KittyRepositoryInterface $repository
    ) {
    }

    public function __invoke(FindAllKittiesQuery $query): KittyCollectionDto
    {
        $ids = $this->repository->findAllKitties();
        $dto = new KittyCollectionDto();
        foreach ($ids as $id) {
            $kittyId = new KittyId($id);
            $stream = $this->repository->getEventsFor($kittyId);
            $kitty = Kitty::hydrate($stream);
            $dto->add(new KittyDto(
                (string) $kitty->id(),
                (string) $kitty->name(),
                (string) $kitty->createdAt(),
                (string) $kitty->updatedAt()
            ));
        }
        return $dto;
    }
}
