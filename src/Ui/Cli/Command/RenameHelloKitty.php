<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace Ui\Cli\Command;

use App\HelloKitty\Application\Command\RenameKitty\RenameKittyCommand;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'hello-kitty:rename',
    description: 'Rename Hello Kitty'
)]
class RenameHelloKitty extends Command
{
    public function __construct(
        private readonly CommandBusInterface $commandBus
    ) {
        parent::__construct(null);
    }

    public function configure(): void
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Hello Kitty id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $style = new SymfonyStyle($input, $output);

        $id = $input->getArgument('id');
        if (!is_string($id) || empty($id)) {
            $style->error('Id must be a string');
            return Command::FAILURE;
        }

        $name = $style->ask('What is your Hello Kitty name? ', 'Kitty White');

        if (!is_string($name) || empty($name)) {
            $style->error('Name must be a string');
            return Command::FAILURE;
        }

        $command = new RenameKittyCommand($id, $name);

        $this->commandBus->dispatch($command);

        $style->success('Hello Kitty renamed!');

        return Command::SUCCESS;
    }
}
