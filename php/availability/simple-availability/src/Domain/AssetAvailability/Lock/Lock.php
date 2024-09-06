<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

abstract readonly class Lock
{
    abstract public function ownerId(): OwnerId;
    public function wasMadeFor(OwnerId $ownerId): bool
    {
        return $this->ownerId()->equals($ownerId);
    }
}