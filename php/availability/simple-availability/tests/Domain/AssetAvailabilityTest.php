<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use PHPUnit\Framework\TestCase;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\AssetAvailability;

use function SoftwareArchetypes\Availability\SimpleAvailability\Tests\Common\someValidDuration;

class AssetAvailabilityTest extends TestCase
{
    public function testShouldCreateAssetAvailabilityWithGivenId(): void
    {
        $assetId = someAssetId();

        self::assertNotNull(AssetAvailability::of($assetId, someClock()));
    }

    public function testShouldActivateTheNewAsset(): void
    {
        $assetAvailability = someNewAsset();

        $result = $assetAvailability->activate();

        self::assertTrue($result->success());
    }

    public function testShouldFailToActivateTheActivatedAsset(): void
    {
        $assetAvailability = someAsset()->thatIsActive()->get();

        $assetAvailability->activate();
        $result = $assetAvailability->activate();

        self::assertTrue($result->failure());
    }

    public function testShouldFailToLockInactiveAsset(): void
    {
        $asset = someNewAsset();
        $owner = someOwnerId();
        $duration = someValidDuration();

        $result = $asset->lockFor($owner, $duration);

        self::assertTrue($result->failure());
    }

    public function testActivatedAssetShouldBeLockedForGivenPeriod(): void
    {
        $asset = someAsset()->thatIsActive()->get();
        $owner = someOwnerId();
        $duration = someValidDuration();

        $result = $asset->lockFor($owner, $duration);

        self::assertTrue($result->success());
    }

    public function testShouldExtendTheLockIndefinitelyWhenGivenHasAlreadyLockedTheAsset(): void
    {
        $ownerId = someOwnerId();
        $asset = someAsset()->thatIsActive()->thatWasLockedByOwnerWith($ownerId)->forSomeValidDuration()->get();

        $result = $asset->lockIndefinitelyFor($ownerId);

        self::assertTrue($result->success());
    }
}
