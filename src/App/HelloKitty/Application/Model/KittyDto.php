<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Model;

use App\Shared\Application\Query\QueryResponseInterface;

final readonly class KittyDto implements QueryResponseInterface
{
    public function __construct(
        public string $id,
        public string $name,
        public string $createdAt,
        public string $updatedAt
    ) {
    }
}
