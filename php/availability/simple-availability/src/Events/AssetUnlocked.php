<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use DateTimeImmutable;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Identifier;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;

final readonly class AssetUnlocked extends BaseDomainEvent
{
    private const string TYPE = 'AssetUnlocked';

    public function __construct(
        public string $assetId,
        public string $ownerId,
        public DateTimeImmutable $unlockedAt,
        DateTimeImmutable $occurredAt,
        ?Identifier $id = null
    ) {
        parent::__construct($id, $occurredAt);
    }

    public static function from(
        AssetId $assetId,
        OwnerId $ownerId,
        DateTimeImmutable $unlockedAt,
        DateTimeImmutable $now
    ): AssetUnlocked {
        return new self((string)$assetId, (string)$ownerId, $unlockedAt, $now);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
