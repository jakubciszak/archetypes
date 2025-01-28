<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

readonly class MaintenanceLock extends Lock
{
    private const string MAINTENANCE_LOCK_OWNER_ID = 'MAINTENANCE';

    public function ownerId(): OwnerId
    {
        return OwnerId::of(self::MAINTENANCE_LOCK_OWNER_ID);
    }
}