<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Model;

use App\Shared\Application\Query\QueryResponseInterface;

class KittyCollectionDto implements QueryResponseInterface
{
    /**
     * @var KittyDto[]
     */
    public array $kitties = [];

    public function add(KittyDto $dto): void
    {
        $this->kitties[] = $dto;
    }

    /**
     * @return KittyDto[]
     */
    public function getKitties(): array
    {
        return $this->kitties;
    }
}
