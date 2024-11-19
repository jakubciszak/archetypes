<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Application;

use DateMalformedStringException;
use Munus\Control\Either;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Clock;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Duration\Duration;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\AssetAvailability;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailabilityRepository;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetActivated;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetActivationRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetLocked;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetLockRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetUnlocked;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetUnlockingRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetWithdrawalRejected;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetWithdrawn;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\DomainEventsPublisher;

use function SoftwareArchetypes\Availability\SimpleAvailability\Common\Result\failure;

readonly class AvailabilityService
{
    public function __construct(
        private AssetAvailabilityRepository $repository,
        private DomainEventsPublisher $domainEventsPublisher,
        private Clock $clock
    ) {
    }

//    /**
//     * @return Either<AssetRegistrationRejected, AssetRegistered>
//     */
//    public function registerAssetWith(AssetId $assetId): Either
//    {
//        $existingAsset = $this->repository->findBy($assetId);
//        if ($existingAsset === null) {
//            $this->repository->save(AssetAvailability::of($assetId));
//            $event = AssetRegistered::from($assetId);
//            $this->domainEventsPublisher->publish($event);
//            return success($event);
//        }
//
//        return failure(AssetRegistrationRejected::dueToAlreadyExistingAssetWith($assetId));
//    }

    /**
     * @return Either<AssetActivationRejected, AssetActivated>
     */
    public function activate(AssetId $assetId): Either
    {
        $asset = $this->repository->findBy($assetId);
        if ($asset !== null) {
            return $this->handle($asset, $asset->activate());
        }

        return failure(AssetActivationRejected::dueToMissingAssetWith($assetId, $this->clock->now()));
    }

    /**
     * @return Either<AssetWithdrawalRejected, AssetWithdrawn>
     */
    public function withdraw(AssetId $assetId): Either
    {
        $asset = $this->repository->findBy($assetId);
        if ($asset !== null) {
            return $this->handle($asset, $asset->withdraw());
        }

        return failure(AssetWithdrawalRejected::dueToMissingAssetWith($assetId, $this->clock->now()));
    }

    public function lock(AssetId $assetId, OwnerId $ownerId, Duration $time): Either
    {
        $asset = $this->repository->findBy($assetId);
        if ($asset !== null) {
            return $this->handle($asset, $asset->lockFor($ownerId, $time));
        }

        return failure(AssetLockRejected::dueToMissingAssetWith($assetId, $ownerId, $this->clock->now()));
    }

    /**
     * @return Either<AssetLockRejected, AssetLocked>
     * @throws DateMalformedStringException
     */
    public function lockIndefinitely(AssetId $assetId, OwnerId $ownerId): Either
    {
        $asset = $this->repository->findBy($assetId);
        if ($asset !== null) {
            return $this->handle($asset, $asset->lockIndefinitelyFor($ownerId));
        }

        return failure(AssetLockRejected::dueToMissingAssetWith($assetId, $ownerId, $this->clock->now()));
    }

    /**
     * @return Either<AssetUnlockingRejected, AssetUnlocked>
     */
    public function unlock(AssetId $assetId, OwnerId $ownerId, \DateTimeImmutable $at): Either
    {
        $asset = $this->repository->findBy($assetId);
        if ($asset !== null) {
            return $this->handle($asset, $asset->unlockFor($ownerId, $at));
        }

        return failure(AssetUnlockingRejected::dueToMissingAssetWith($assetId, $ownerId, $this->clock->now()));
    }

    public function unlockIfOverdue(AssetAvailability $assetAvailability): void
    {
        $event = $assetAvailability->unlockIfOverdue();
        if ($event !== null) {
            $this->repository->save($assetAvailability);
            $this->domainEventsPublisher->publish($event);
        }
    }

    private function handle(AssetAvailability $asset, Either $executionResult): Either
    {
        if ($executionResult->isRight()) {
            $this->repository->save($asset);
            $this->domainEventsPublisher->publish($executionResult->get());
        }
        return $executionResult;
    }
}