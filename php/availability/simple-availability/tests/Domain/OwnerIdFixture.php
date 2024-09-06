<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

function someOwnerId(): OwnerId
{
    return OwnerId::of('owner-id');
}