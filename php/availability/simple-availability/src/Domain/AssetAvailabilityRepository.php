<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Domain;

use Munus\Collection\Stream;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\AssetAvailability;

interface AssetAvailabilityRepository
{
    public function save(AssetAvailability $assetAvailability): void;

    public function findBy(AssetId $assetId): ?AssetAvailability;

    public function findOverdue(): Stream;

}