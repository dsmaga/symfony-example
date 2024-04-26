<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Stringable;

class NullableStringValueObject implements Stringable
{
    public function __construct(private ?string $value)
    {
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value ?? '';
    }
}
