<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;

function someAssetId(): AssetId
{
    return AssetId::of('asset-id');
}
