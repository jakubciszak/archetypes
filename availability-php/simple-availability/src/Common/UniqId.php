<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common;

use Ramsey\Uuid\Rfc4122\UuidV4;

abstract readonly class UniqId
{
    private function __construct(public string $value)
    {
    }

    public static function new(): static
    {
        $uuid = UuidV4::uuid4();
        return new static($uuid->toString());
    }

    public static function of(string $value): static
    {
        $uuid = UuidV4::fromString($value);
        return new static($uuid->toString());
    }
}
