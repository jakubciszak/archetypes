<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common;

use Ramsey\Uuid\Uuid;
use Stringable;

readonly class Identifier implements Stringable, Equatable
{
    private function __construct(public string $value)
    {

    }

    public static function of(string $value): static
    {
        return new static($value);
    }

    public static function generate(): static
    {
        return new static((string)Uuid::uuid4());
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(Equatable $other): bool
    {
        if (!($other instanceof self)) {
            return false;
        }
        return $other->value === $this->value;
    }
}
