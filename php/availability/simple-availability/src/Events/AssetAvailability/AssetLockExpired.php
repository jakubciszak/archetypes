<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events\AssetAvailability;

use SoftwareArchetypes\Availability\SimpleAvailability\Common\Identifier;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Events\BaseDomainEvent;

readonly class AssetLockExpired extends BaseDomainEvent
{
    private const string TYPE = 'AssetLockExpired';

    public function __construct(
        public string $assetId,
        \DateTimeImmutable $occurredAt,
        ?Identifier $id = null
    ) {
        parent::__construct($id, $occurredAt);
    }

    public static function from(
        AssetId $assetId,
        \DateTimeImmutable $occurredAt
    ): self {
        return new self($assetId, $occurredAt);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
