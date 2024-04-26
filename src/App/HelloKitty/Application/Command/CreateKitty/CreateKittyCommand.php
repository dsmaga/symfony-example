<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Command\CreateKitty;

use App\Shared\Application\Command\CommandInterface;

final readonly class CreateKittyCommand implements CommandInterface
{
    public function __construct(
        public string $id,
        public string $name
    ) {
    }
}
