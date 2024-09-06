<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Tests\Domain;

use SoftwareArchetypes\Availability\SimpleAvailability\Common\Clock;

function someClock(string $dateTime = '2021-01-01T00:00:00'): Clock
{
    return new class($dateTime) implements Clock {
        private \DateTimeImmutable $now;

        public function __construct(string $dateTime)
        {
            $this->now = new \DateTimeImmutable($dateTime);
        }

        public function now(): \DateTimeImmutable
        {
            return new $this->now;
        }
    };
}
