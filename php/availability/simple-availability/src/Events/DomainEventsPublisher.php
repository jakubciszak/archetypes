<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

interface DomainEventsPublisher
{
    public function publish(DomainEvent $event): void;
}