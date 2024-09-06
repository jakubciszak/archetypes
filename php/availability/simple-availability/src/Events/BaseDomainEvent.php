<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Identifier;

readonly abstract class BaseDomainEvent implements DomainEvent
{
    private Identifier $id;
    private DateTimeImmutable $occurredAt;

    public function __construct(?Identifier $id = null, ?DateTimeImmutable $occurredAt = null)
    {
        $this->id = $id ?? Identifier::generate();
        $this->occurredAt = $occurredAt ?? new DateTimeImmutable();
    }

    public function getId(): Identifier
    {
        return $this->id;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}