<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetAvailability\AssetAvailability;

function someNewAsset(): AssetAvailability
{
    return AssetAvailabilityBuilder::with(someAssetId())->get();
}

function someAsset(): AssetAvailabilityBuilder
{
    return AssetAvailabilityBuilder::with(someAssetId());
}