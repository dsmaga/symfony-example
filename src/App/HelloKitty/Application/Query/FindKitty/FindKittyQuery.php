<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Query\FindKitty;

use App\Shared\Application\Query\QueryInterface;

final readonly class FindKittyQuery implements QueryInterface
{
    public function __construct(
        public string $id
    ) {
    }
}
