<?php

namespace Tests\Domain\Fixtures;

use Ramsey\Uuid\Uuid;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

final class OwnerIdFixture
{
    public static function someOwnerId(): OwnerId
    {
        return OwnerId::of(self::someOwnerIdValue());
    }

    public static function someOwnerIdValue(): string
    {
        return Uuid::uuid4()->toString();
    }
}
