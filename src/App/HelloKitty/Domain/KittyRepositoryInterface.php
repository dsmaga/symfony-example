<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Domain;

use App\HelloKitty\Domain\ValueObject\KittyId;
use App\Shared\Domain\Repository\EventStoreRepositoryInterface;

interface KittyRepositoryInterface extends EventStoreRepositoryInterface
{
    /** @return string[] */
    public function findAllKitties(): array;

    public function clear(): void;

    public function exists(KittyId $id): bool;
}
