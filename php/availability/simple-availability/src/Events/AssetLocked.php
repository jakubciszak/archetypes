<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use DateTimeImmutable;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Identifier;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\Reason;

final readonly class AssetLocked extends BaseDomainEvent
{
    private const string TYPE = 'AssetLocked';

    public function __construct(
        public string $assetId,
        public string $ownerId,
        public DateTimeImmutable $validUtil,
        DateTimeImmutable $occurredAt,
        ?Identifier $id = null
    ) {
        parent::__construct($id, $occurredAt);
    }

    public static function from(
        AssetId $assetId,
        OwnerId $ownerId,
        DateTimeImmutable $validUtil,
        DateTimeImmutable $now
    ): AssetLocked {
        return new self((string)$assetId, (string)$ownerId, $validUtil, $now);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
