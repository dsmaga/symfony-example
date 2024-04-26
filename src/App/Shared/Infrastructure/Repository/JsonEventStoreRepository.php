<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Infrastructure\Repository;

use App\Shared\Domain\Event\AbstractEvent;
use App\Shared\Domain\Event\EventStream;
use App\Shared\Domain\Repository\EventStoreRepositoryInterface;
use App\Shared\Domain\ValueObject\Uuid;
use RuntimeException;
use InvalidArgumentException;

class JsonEventStoreRepository implements EventStoreRepositoryInterface
{
    /**
     * @var array<array{aggregateId:string, data:array<string, mixed>, eventClass:string,
     *       eventId: string, eventName: string, createdAt: string}>
     */
    private array $data = [];
    private bool $loaded = false;

    public function __construct(private readonly string $filePath)
    {
        if (!is_file($this->filePath)) {
            $this->createFile();
        }
    }

    private function createFile(): void
    {
        $dir = dirname($this->filePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($this->filePath, '[]');
    }

    private function loadDatabase(): void
    {
        if ($this->loaded) {
            return;
        }
        if (is_file($this->filePath)) {
            $json = file_get_contents($this->filePath);
            if (!$json || !json_validate($json)) {
                throw new RuntimeException('Wrong database format');
            }
            /** @var array<array{aggregateId:string, data:array<string, mixed>, eventClass:string,
             *     eventId: string, eventName: string, createdAt: string}> $data */
            $data = json_decode($json, true);
            $this->data = $data;
        }
        $this->loaded = true;
    }

    private function saveDatabase(): void
    {
        $json = json_encode($this->data, JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $json);
    }

    public function getEventsFor(Uuid $aggregateId): EventStream
    {
        $this->loadDatabase();
        $aggregateIdStr = (string) $aggregateId;
        $aggregateItems = array_filter($this->data, fn (array $item) => $item['aggregateId'] === $aggregateIdStr);
        $events = array_map($this->loadEventFromArrayItem(...), $aggregateItems);
        return new EventStream($aggregateId, $events);
    }

    /**
     * @param array{aggregateId:string, data:array<string, mixed>, eventClass:string,
     *       eventId: string, eventName: string, createdAt: string} $item
     */
    private function loadEventFromArrayItem(array $item): AbstractEvent
    {

        $eventClass = $item['eventClass'];
        if (!is_subclass_of($eventClass, AbstractEvent::class)) {
            throw new InvalidArgumentException('Wrong event class');
        }
        return $eventClass::deserialize(
            $item['aggregateId'],
            $item['data'],
            $item['eventId'],
            $item['createdAt']
        );
    }

    public function save(AbstractEvent $event): void
    {
        $this->loadDatabase();

        $aggregateId = (string)$event->aggregateId();

        /** @var array{aggregateId:string, data:array<string, mixed>, eventClass:string, eventId: string,
         *     eventName: string, createdAt: string} $item */
        $item = [
            'aggregateId' => $aggregateId,
            'data' => $event->serialize(),
            'eventClass' => get_class($event),
            'eventId' => (string)$event->eventId(),
            'eventName' => $event->eventName(),
            'createdAt' => (string)$event->eventCreatedAt(),
        ];
        $this->data[] = $item;
        $this->saveDatabase();
    }

    /**
     * @return string[]
     */
    public function keys(): array
    {
        $this->loadDatabase();
        return array_unique(
            array_map(
                fn (array $item) => $item['aggregateId'],
                $this->data
            )
        );
    }

    protected function doClear(): void
    {
        $this->data = [];
        $this->saveDatabase();
    }
}
