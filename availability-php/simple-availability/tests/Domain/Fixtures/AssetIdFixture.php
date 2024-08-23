<?php

namespace Tests\Domain\Fixtures;

use Ramsey\Uuid\Uuid;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;

final class AssetIdFixture
{
    public static function someAssetId(): AssetId
    {
        return AssetId::of(self::someAssetIdValue());
    }

    public static function someAssetIdValue(): string
    {
        return Uuid::uuid4()->toString();
    }
}

