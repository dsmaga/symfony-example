<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace Ui\Cli\Command;

use App\HelloKitty\Application\Command\CreateKitty\CreateKittyCommand;
use App\HelloKitty\Domain\KittyRepositoryInterface;
use App\Shared\Application\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\UuidV4;

#[AsCommand('hello-kitty:fill')]
class FillHelloKitty extends Command
{
    public function __construct(
        private readonly KittyRepositoryInterface $kittyRepository,
        private readonly CommandBusInterface $commandBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $style = new SymfonyStyle($input, $output);

        $this->kittyRepository->clear();

        $kitties = [
            'Kitty White',
            'Mimmy',
            'George',
            'Mary',
            'Anthony',
            'Margaret',
            'Charmmy Kitty',
            'Dear Daniel',
        ];

        foreach ($kitties as $kitty) {
            $id = (string) UuidV4::v4();
            $command = new CreateKittyCommand($id, $kitty);
            $this->commandBus->dispatch($command);
        }

        $style->success('Hello Kitty filled!');

        return Command::SUCCESS;
    }
}
