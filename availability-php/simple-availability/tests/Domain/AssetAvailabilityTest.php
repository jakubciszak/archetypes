<?php

namespace Tests\Domain;

use PHPUnit\Framework\TestCase;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability;
use Tests\Domain\Fixtures\AssetIdFixture;

class AssetAvailabilityTest extends TestCase
{
    public function testShouldCreateAssetAvailabilityWhenGivenId(): void
    {
        $assetId = AssetIdFixture::someAssetId();
        self::assertNotNull(AssetAvailability::of($assetId));
    }
}
