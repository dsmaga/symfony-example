<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Command\RenameKitty;

use App\Shared\Application\Command\CommandHandlerInterface;

final readonly class RenameKittyHandler implements CommandHandlerInterface
{
    public function __construct(
        private RenameKittyService $service
    ) {
    }

    public function __invoke(RenameKittyCommand $command): void
    {
        $this->service->rename(
            $command->id,
            $command->name
        );
    }
}
