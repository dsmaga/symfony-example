<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Infrastructure\Query;

use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Application\Query\QueryInterface;
use App\Shared\Application\Query\QueryResponseInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final readonly class QueryBus implements QueryBusInterface
{
    public function __construct(private MessageBusInterface $queryBus)
    {
    }

    public function dispatch(QueryInterface $query): ?QueryResponseInterface
    {
        try {
            $envelope = $this->queryBus->dispatch($query);
            /** @var HandledStamp $handled */
            $handled = $envelope->last(HandledStamp::class);
            $response = $handled->getResult();
            if ($response === null || $response instanceof QueryResponseInterface) {
                return $response;
            }
            throw new \RuntimeException('Query handler must return QueryResponseInterface|null');
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }
}
