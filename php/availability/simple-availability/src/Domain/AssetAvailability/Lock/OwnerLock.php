<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock;

use DateTimeImmutable;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

readonly class OwnerLock extends Lock
{
    public function __construct(private OwnerId $ownerId, private DateTimeImmutable $util)
    {

    }

    public function ownerId(): OwnerId
    {
        return $this->ownerId;
    }

    public function getUtil(): DateTimeImmutable
    {
        return $this->util;
    }
}