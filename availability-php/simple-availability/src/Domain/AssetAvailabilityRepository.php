<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain;

use Munus\Collection\Stream;

interface AssetAvailabilityRepository
{
    public function save(AssetAvailability $assetAvailability): void;

    public function find(AssetId $assetId): ?AssetAvailability;

    /**
     * @return Stream<AssetAvailability>
     */
    public function findByOwnerId(OwnerId $ownerId): Stream;
}
