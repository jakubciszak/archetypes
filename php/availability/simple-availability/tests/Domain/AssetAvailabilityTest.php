<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use DateMalformedStringException;
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

        self::assertTrue($result->isRight());
        self::assertEquals('AssetActivated', $result->get()->getType());
    }

    public function testShouldFailToActivateTheActivatedAsset(): void
    {
        $assetAvailability = someAsset()->thatIsActive()->get();

        $assetAvailability->activate();
        $result = $assetAvailability->activate();

        self::assertTrue($result->isLeft());
        self::assertEquals('AssetActivationRejected', $result->getLeft()->getType());
    }

    public function testShouldFailToLockInactiveAsset(): void
    {
        $asset = someNewAsset();
        $owner = someOwnerId();
        $duration = someValidDuration();

        $result = $asset->lockFor($owner, $duration);

        self::assertTrue($result->isLeft());
        self::assertEquals('AssetLockRejected', $result->getLeft()->getType());
    }

    public function testActivatedAssetShouldBeLockedForGivenPeriod(): void
    {
        $asset = someAsset()->thatIsActive()->get();
        $owner = someOwnerId();
        $duration = someValidDuration();

        $result = $asset->lockFor($owner, $duration);

        self::assertTrue($result->isRight());
        self::assertEquals('AssetLocked', $result->get()->getType());
    }

    /**
     * @throws DateMalformedStringException
     */
    public function testShouldExtendTheLockIndefinitelyWhenGivenHasAlreadyLockedTheAsset(): void
    {
        $ownerId = someOwnerId();
        $asset = someAsset()->thatIsActive()->thatWasLockedByOwnerWith($ownerId)->forSomeValidDuration()->get();

        $result = $asset->lockIndefinitelyFor($ownerId);

        self::assertTrue($result->isRight());
        self::assertEquals('AssetLocked', $result->get()->getType());
    }

    public function testShouldWithdrawTheAsset(): void
    {
        $asset = someAsset()->thatIsActive()->get();

        $result = $asset->withdraw();

        self::assertTrue($result->isRight());
        self::assertEquals('AssetWithdrawn', $result->get()->getType());
    }

    public function testShouldFailToWithdrawTheLockedAsset(): void
    {
        $asset = someAsset()->thatIsActive()->thatWasLockedByOwnerWith(someOwnerId())->forSomeValidDuration()->get();

        $result = $asset->withdraw();

        self::assertTrue($result->isLeft());
        self::assertEquals('AssetWithdrawalRejected', $result->getLeft()->getType());
    }

    public function testShouldFailToLockTheLockedAsset(): void
    {
        $asset = someAsset()->thatIsActive()->thatWasLockedByOwnerWith(someOwnerId())->forSomeValidDuration()->get();

        $result = $asset->lockFor(someOwnerId(), someValidDuration());

        self::assertTrue($result->isLeft());
        self::assertEquals('AssetLockRejected', $result->getLeft()->getType());
    }

    public function testShouldUnlockOverdueAsset(): void
    {
        $asset = someAsset()->thatIsActive()->thatWasLockedByOwnerWith(someOwnerId())->forSomeValidDuration()->get();

        self::assertNotNull($asset->unlockIfOverdue());
    }
}
