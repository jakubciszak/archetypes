<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common\Duration;

enum Unit: string
{
    case DAYS = 'd';
    case HOURS = 'h';
    case MINUTES = 'm';
    case SECONDS = 's';

}