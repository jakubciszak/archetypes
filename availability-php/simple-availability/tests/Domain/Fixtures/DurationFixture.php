<?php

namespace Tests\Domain\Fixtures;

use DateInterval;

final class DurationFixture
{
    public static function someValidDuration(): DateInterval
    {
        return new DateInterval('PT15M');
    }
}

