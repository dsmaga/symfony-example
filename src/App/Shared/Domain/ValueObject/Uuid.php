<?php

/**
 * Created by dsmaga at 26.04.2024
 */

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;
use Symfony\Component\Uid\UuidV4;

class Uuid implements Stringable
{
    final public function __construct(private string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    /**
     * @return static
     */
    public static function create(?string $uuid = null): static
    {
        if ($uuid === null) {
            $uuid = (string) UuidV4::v4();
        } elseif (!self::isValid($uuid)) {
            throw new InvalidArgumentException('Invalid UUID');
        }
        return new static($uuid);
    }

    public static function isValid(string $uuid): bool
    {
        return UuidV4::isValid($uuid);
    }

    public function __toString()
    {
        return $this->value;
    }
}
