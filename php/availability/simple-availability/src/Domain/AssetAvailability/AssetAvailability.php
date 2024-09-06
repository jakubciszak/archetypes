<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability;

use SoftwareArchetypes\Availability\SimpleAvailability\Common\Clock;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Duration\Duration;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Result\Result;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock\Lock;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock\MaintenanceLock;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock\OwnerLock;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\Reason;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetActivationRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetActivated;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetLocked;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetLockRejected;

use function SoftwareArchetypes\Availability\SimpleAvailability\Common\Result\failure;
use function SoftwareArchetypes\Availability\SimpleAvailability\Common\Result\success;


final class AssetAvailability
{
    private ?Lock $currentLock;
    private function __construct(
        private readonly AssetId $assetId,
        private readonly Clock $clock
    ) {
        $this->currentLock = new MaintenanceLock();
    }

    public static function of(AssetId $assetId, Clock $clock): self
    {
        return new self($assetId, $clock);
    }

    public function activate(): Result
    {
        if ($this->currentLock instanceof MaintenanceLock) {
            $this->currentLock = null;
            return success(AssetActivated::from($this->assetId, $this->clock->now()));
        }
        return failure(AssetActivationRejected::from($this->assetId, Reason::ASSET_ALREADY_ACTIVATED_REASON, $this->clock->now()));
    }

    /**
     * @return Result<AssetLockRejected, AssetLocked>
     */
    public function lockFor(OwnerId $ownerId, Duration $duration): Result
    {
        if ($this->currentLock === null) {
            $validUtil = $this->clock->now()->add($duration->toInterval());
            $this->currentLock = new OwnerLock($ownerId, $validUtil);
            return success(AssetLocked::from($this->assetId, $ownerId, $validUtil, $this->clock->now()));
        }
        return failure(AssetLockRejected::from($this->assetId, $ownerId, Reason::ASSET_LOCKED_REASON, $this->clock->now()));
    }

    public function lockIndefinitelyFor(OwnerId $ownerId): Result
    {
        if ($this->thereIsAnActiveLockFor($ownerId)) {
            $validUtil = $this->clock->now()->modify('+365 days');
            $this->currentLock = new OwnerLock($ownerId, $validUtil);
            return success(AssetLocked::from($this->assetId, $ownerId, $validUtil, $this->clock->now()));
        }
        return failure(AssetLockRejected::from($this->assetId, $ownerId, Reason::NO_LOCK_DEFINED_FOR_OWNER_REASON, $this->clock->now()));
    }

    private function thereIsAnActiveLockFor(OwnerId $ownerId): bool
    {
        return $this->currentLock->wasMadeFor($ownerId);
    }
}