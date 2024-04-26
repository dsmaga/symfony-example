<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Infrastructure\Repository;

use App\HelloKitty\Domain\KittyRepositoryInterface;
use App\HelloKitty\Domain\ValueObject\KittyId;
use App\Shared\Infrastructure\Repository\JsonEventStoreRepository;

class KittyRepository extends JsonEventStoreRepository implements KittyRepositoryInterface
{
    public function findAllKitties(): array
    {
        return $this->keys();
    }

    public function clear(): void
    {
        $this->doClear();
    }

    public function exists(KittyId $id): bool
    {
        return in_array((string)$id, $this->keys());
    }
}
