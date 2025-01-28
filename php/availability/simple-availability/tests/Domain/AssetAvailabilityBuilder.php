<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use Closure;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\AssetAvailability;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

readonly class AssetAvailabilityBuilder
{
    private function __construct(private AssetAvailability $assetAvailability)
    {}

    public static function with(AssetId $assetId): self
    {
        return new self(AssetAvailability::of($assetId, someClock()));
    }

    public function thatIsActive(): self
    {
        $this->assetAvailability->activate();
        return $this;
    }

    public function get(): AssetAvailability
    {
        return $this->assetAvailability;
    }

    public function thatWasLockedByOwnerWith(OwnerId $ownerId): AssetAvailabilityLockBuilder
    {
        return new AssetAvailabilityLockBuilder($this, $ownerId);
    }

    protected function executeOnAsset(Closure $assetFunction): self
    {
        $assetFunction($this->assetAvailability);
        return $this;
    }
}

