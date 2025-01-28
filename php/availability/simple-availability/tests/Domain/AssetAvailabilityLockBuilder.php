<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\AssetAvailability;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

use function SoftwareArchetypes\Availability\SimpleAvailability\Tests\Common\someValidDuration;

final readonly class AssetAvailabilityLockBuilder
{
    public function __construct(
        private AssetAvailabilityBuilder $parent,
        private OwnerId $ownerId
    ) {
    }

    public function forSomeValidDuration(): AssetAvailabilityBuilder
    {
       $closure = function(OwnerId $ownerId) {
           $this->executeOnAsset(
               fn(AssetAvailability $assetAvailability) => $assetAvailability->lockFor($ownerId, someValidDuration())
           );
           return $this;
       };
       $bounded = $closure->bindTo($this->parent, AssetAvailabilityBuilder::class);
       return $bounded($this->ownerId);
    }
}