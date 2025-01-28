<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common;

interface Clock
{
    public function now(): \DateTimeImmutable;
}