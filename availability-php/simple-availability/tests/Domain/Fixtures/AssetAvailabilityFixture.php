<?php

namespace Tests\Domain\Fixtures;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;

final class AssetAvailabilityFixture
{
    public static function someAssetAvailability(): AssetAvailability
    {
        return AssetAvailability::of(self::someAssetId());
    }

    public static function someAssetId(): AssetId
    {
        return AssetIdFixture::someAssetId();
    }
}
