<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common;

interface Equatable
{
    public function equals(Equatable $other): bool;
}