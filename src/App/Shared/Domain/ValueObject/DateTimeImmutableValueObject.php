<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Assert\Assertion;
use DateTimeImmutable;
use Stringable;

class DateTimeImmutableValueObject implements Stringable
{
    final public function __construct(
        private DateTimeImmutable $value
    ) {
    }

    public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value->format(DATE_ATOM);
    }

    /**
     * @return static
     */
    public static function create(DateTimeImmutable|string $dateTime = new DateTimeImmutable()): static
    {
        if (!$dateTime instanceof DateTimeImmutable) {
            Assertion::nullOrDate($dateTime, DATE_ATOM);
            $dateTime = new DateTimeImmutable($dateTime);
        }
        return new static($dateTime);
    }
}
