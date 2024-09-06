<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

readonly class WithdrawalLock extends Lock
{

    private const string WITHDRAWAL_LOCK_OWNER_ID = 'WITHDRAWAL';

    public function ownerId(): OwnerId
    {
        return OwnerId::of(self::WITHDRAWAL_LOCK_OWNER_ID);
    }
}