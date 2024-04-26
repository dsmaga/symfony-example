<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\HelloKitty\Application\Command\CreateKitty;

use App\Shared\Application\Command\CommandHandlerInterface;

final readonly class CreateKittyHandler implements CommandHandlerInterface
{
    public function __construct(
        private CreateKittyService $service
    ) {
    }

    public function __invoke(CreateKittyCommand $command): void
    {
        $this->service->create(
            $command->id,
            $command->name
        );
    }
}
