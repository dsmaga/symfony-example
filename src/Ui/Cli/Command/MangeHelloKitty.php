<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace Ui\Cli\Command;

use App\HelloKitty\Application\Model\KittyCollectionDto;
use App\HelloKitty\Application\Query\FindAllKitties\FindAllKittiesQuery;
use App\Shared\Application\Query\QueryBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'hello-kitty:manage',
    description: 'Hello Kitty Manager'
)]
class MangeHelloKitty extends Command
{
    private const EXIT = -1;

    public function __construct(
        private readonly QueryBusInterface $queryBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        do {
            $status = $this->mainView($input, $output);
        } while ($status !== self::EXIT);
        return $status;
    }

    private function mainView(InputInterface $input, OutputInterface $output): int
    {
        $output->write("\033\143");

        $style = new SymfonyStyle($input, $output);

        $kitties = $this->getKitties();

        $style->title('Hello Kitty Manager');

        $style->table(
            ['Id', 'Name', 'Created At', 'Updated At'],
            array_map(function ($kitty) {
                return [
                    $kitty->id,
                    $kitty->name,
                    $kitty->createdAt,
                    $kitty->updatedAt
                ];
            }, $kitties->getKitties())
        );

        $choices = [
            'create' => 'Create Hello Kitty',
            'rename' => 'Rename Hello Kitty',
            'fill' => 'Fill Hello Kitty Base',
            'exit' => 'Exit'
        ];

        $choice = $style->choice('Choose action', $choices);

        return match ($choice) {
            'create' => $this->createHelloKitty($output),
            'rename' => $this->renameHelloKitty($input, $output),
            'fill' => $this->fillHelloKitty($output),
            'exit' => self::EXIT,
            default => Command::SUCCESS,
        };
    }

    private function getKitties(): KittyCollectionDto
    {
        /** @var KittyCollectionDto $collection */
        $collection = $this->queryBus->dispatch(new FindAllKittiesQuery());
        return $collection;
    }

    private function fillHelloKitty(OutputInterface $output): int
    {
        $output->write("\033\143");

        $input = new ArrayInput(['command' => 'hello-kitty:fill']);
        return (int) $this->getApplication()?->doRun($input, $output);
    }

    private function createHelloKitty(OutputInterface $output): int
    {
        $output->write("\033\143");

        $input = new ArrayInput(['command' => 'hello-kitty:create']);
        return (int) $this->getApplication()?->doRun($input, $output);
    }

    private function renameHelloKitty(InputInterface $input, OutputInterface $output): int
    {
        $output->write("\033\143");

        $style = new SymfonyStyle($input, $output);

        $kitties = $this->getKitties();
        $choices = array_reduce($kitties->getKitties(), function ($carry, $kitty) {
            $carry[$kitty->id] = $kitty->name;
            return $carry;
        }, []);
        $choices['return'] = 'Return';

        $id = $style->choice('Choose Hello Kitty to rename', $choices);

        if ($id === 'return') {
            return Command::SUCCESS;
        }

        $greetInput = new ArrayInput([
            'command' => 'hello-kitty:rename',
            'id' => $id
        ]);
        return (int) $this->getApplication()?->doRun($greetInput, $output);
    }
}
