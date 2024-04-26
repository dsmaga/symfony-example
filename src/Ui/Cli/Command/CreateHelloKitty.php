<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace Ui\Cli\Command;

use App\HelloKitty\Application\Command\CreateKitty\CreateKittyCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\UuidV4;

#[AsCommand(
    name: 'hello-kitty:create',
    description: 'Create Hello Kitty'
)]
class CreateHelloKitty extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $name = $style->ask('What is your Hello Kitty name? ', 'Kitty White');
        if (!is_string($name) || empty($name)) {
            $style->error('Name must be a string');
            return Command::FAILURE;
        }

        $id = (string) UuidV4::v4();
        $command = new CreateKittyCommand($id, $name);

        $this->commandBus->dispatch($command);

        $style->success('Hello Kitty created!');

        return Command::SUCCESS;
    }
}
