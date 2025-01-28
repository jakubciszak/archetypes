<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability;

use DateMalformedStringException;
use Munus\Control\Either;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Clock;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Duration\Duration;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock\Lock;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock\MaintenanceLock;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock\OwnerLock;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\Lock\WithdrawalLock;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\Reason;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetActivated;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetActivationRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetAvailability\AssetLockExpired;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetLocked;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetLockRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetUnlocked;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetUnlockingRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetWithdrawalRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetWithdrawn;

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

    /**
     * @return Either<AssetActivationRejected, AssetActivated>
     */
    public function activate(): Either
    {
        if ($this->currentLock instanceof MaintenanceLock) {
            $this->currentLock = null;
            return success(AssetActivated::from($this->assetId, $this->clock->now()));
        }
        return failure(AssetActivationRejected::from($this->assetId, Reason::ASSET_ALREADY_ACTIVATED_REASON,
            $this->clock->now()));
    }

    /**
     * @return Either<AssetLockRejected, AssetLocked>
     */
    public function lockFor(OwnerId $ownerId, Duration $duration): Either
    {
        if ($this->currentLock === null) {
            $validUtil = $this->clock->now()->add($duration->toInterval());
            $this->currentLock = new OwnerLock($ownerId, $validUtil);
            return success(AssetLocked::from($this->assetId, $ownerId, $validUtil, $this->clock->now()));
        }
        return failure(AssetLockRejected::from($this->assetId, $ownerId, Reason::ASSET_LOCKED_REASON,
            $this->clock->now()));
    }

    /**
     * @return Either<AssetWithdrawalRejected, AssetWithdrawn>
     */
    public function withdraw(): Either
    {
        if ($this->currentLock === null || $this->currentLock instanceof MaintenanceLock) {
            $this->currentLock = new WithdrawalLock();
            return success(AssetWithdrawn::from($this->assetId, $this->clock->now()));
        }
        return failure(AssetWithdrawalRejected::dueToAssetLocked($this->assetId, $this->clock->now()));
    }

    /**
     * @return Either<AssetLockRejected, AssetLocked>
     * @throws DateMalformedStringException
     */
    public function lockIndefinitelyFor(OwnerId $ownerId): Either
    {
        if ($this->thereIsAnActiveLockFor($ownerId)) {
            $validUtil = $this->clock->now()->modify('+365 days');
            $this->currentLock = new OwnerLock($ownerId, $validUtil);
            return success(AssetLocked::from($this->assetId, $ownerId, $validUtil, $this->clock->now()));
        }
        return failure(AssetLockRejected::from($this->assetId, $ownerId, Reason::NO_LOCK_DEFINED_FOR_OWNER_REASON,
            $this->clock->now()));
    }

    private function thereIsAnActiveLockFor(OwnerId $ownerId): bool
    {
        return $this->currentLock->wasMadeFor($ownerId);
    }

    public function unlockFor(OwnerId $ownerId, \DateTimeImmutable $at): Either
    {
        if ($this->thereIsAnActiveLockFor($ownerId)) {
            $this->currentLock = null;
            return success(AssetUnlocked::from($this->assetId, $ownerId, $at, $this->clock->now()));
        }
        return failure(AssetUnlockingRejected::dueToNoLockOnTheAsset($this->assetId, $ownerId, $this->clock->now()));
    }

    public function unlockIfOverdue(): ?AssetLockExpired
    {
        if ($this->currentLock !== null) {
            $this->currentLock = null;
            return AssetLockExpired::from($this->assetId, $this->clock->now());
        }
        return null;
    }
}