<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Common;

use SoftwareArchetypes\Availability\SimpleAvailability\Common\Duration\Duration;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Duration\Unit;

function someValidDuration(): Duration
{
    return Duration::of(1, Unit::HOURS);
}