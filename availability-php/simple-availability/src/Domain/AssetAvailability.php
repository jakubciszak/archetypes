<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain;

use SoftwareArchetypes\Availability\SimpleAvailability\Common\Result;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Success;

readonly class AssetAvailability
{
    private function __construct(private AssetId $assetId)
    {
    }

    public static function of(AssetId $assetId): self
    {
        return new self($assetId);
    }

    public function activate(): Result
    {
        return Success::createSuccess('');
    }

    public function unlockFor(
        OwnerId $lastLockOwnerId,
        \DateTimeImmutable $param
    ): Result {
        return Success::createSuccess('');
    }

    public function lockFor(
        OwnerId $lockOwnerId,
        \DateInterval $someValidDuration
    ): Result {
        return Success::createSuccess('');
    }
}
