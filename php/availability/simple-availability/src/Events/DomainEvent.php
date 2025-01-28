<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use DateTimeImmutable;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Identifier;

interface DomainEvent
{
    public function getId(): Identifier;
    public function getOccurredAt(): DateTimeImmutable;
    public function getType(): string;
}