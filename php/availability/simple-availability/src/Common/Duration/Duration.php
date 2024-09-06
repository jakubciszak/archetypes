<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common\Duration;

use DateInterval;
use DateMalformedIntervalStringException;

final readonly class Duration
{
    private function __construct(private DateInterval $interval)
    {
    }

    /**
     * @throws DateMalformedIntervalStringException
     */
    public static function of(int $value, Unit $unit): self
    {
        $interval = match ($unit) {
            Unit::DAYS => "P{$value}D",
            Unit::HOURS => "PT{$value}H",
            Unit::MINUTES => "PT{$value}M",
            Unit::SECONDS => "PT{$value}S",
        };
        return new self(new DateInterval($interval));
    }

    public function toInterval(): DateInterval
    {
        return $this->interval;
    }
}