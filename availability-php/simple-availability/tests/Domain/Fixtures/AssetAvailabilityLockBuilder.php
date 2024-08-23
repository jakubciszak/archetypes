<?php

namespace Tests\Domain\Fixtures;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

final class AssetAvailabilityLockBuilder extends AssetBuilder
{
    private static AssetAvailabilityBuilder $parent;
    private static OwnerId $lockOwnerId;

    public function __construct(
        AssetAvailabilityBuilder $parent,
        OwnerId $ownerId
    ) {
        self::$parent = $parent;
        self::$lockOwnerId = $ownerId;
    }

    public function forSomeValidDuration(): AssetAvailabilityBuilder
    {
        self::$parent->executeOnAsset(
            fn(
                AssetAvailability $asset
            ) => $asset->lockFor(
                self::$lockOwnerId,
                DurationFixture::someValidDuration()
            )
        );
        return self::$parent;
    }
}
