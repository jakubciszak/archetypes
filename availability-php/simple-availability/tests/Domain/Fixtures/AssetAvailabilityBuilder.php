<?php

namespace Tests\Domain\Fixtures;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

final class AssetAvailabilityBuilder extends AssetBuilder
{
    private static AssetAvailability $assetAvailability;
    private static OwnerId $lastLockOwnerId;

    public static function with(AssetId $assetId): self
    {
        self::$assetAvailability = AssetAvailability::of($assetId);
        return new self();
    }

    public function thatIsActive(): self
    {
        self::$assetAvailability->activate();
        return $this;
    }

    public function thatWasLockedByOwnerWith(OwnerId $ownerId): AssetAvailabilityLockBuilder
    {
        return new AssetAvailabilityLockBuilder(
            $this,
            $ownerId
        );
    }

    public function thatWasLockedBySomeOwner(): AssetAvailabilityLockBuilder
    {
        self::$lastLockOwnerId = OwnerIdFixture::someOwnerId();
        return new AssetAvailabilityLockBuilder(
            $this,
            self::$lastLockOwnerId
        );
    }

    public function get(): AssetAvailability
    {
        return self::$assetAvailability;
    }

    public function thenUnlocked(): self
    {
        self::$assetAvailability->unlockFor(
            self::$lastLockOwnerId,
            new \DateTimeImmutable()
        );
        return $this;
    }

    protected function executeOnAsset(\Closure $assetFunction): static
    {
        $assetFunction(self::$assetAvailability);
        return $this;
    }
}
