<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use SoftwareArchetypes\Availability\SimpleAvailability\Common\Identifier;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\OwnerId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\Reason;

final readonly class AssetLockRejected extends BaseDomainEvent
{
    private const string TYPE = 'AssetLockRejected';
    public function __construct(
        public string $assetId,
        public string $ownerId,
        public string $reason,
        \DateTimeImmutable $occurredAt,
        ?Identifier $id = null
    ) {
        parent::__construct($id, $occurredAt);
    }

    public static function dueToMissingAssetWith(AssetId $assetId, OwnerId $ownerId, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, (string)$ownerId, Reason::ASSET_IS_MISSING_REASON->value, $occurredAt);
    }

    public static function from(AssetId $assetId, OwnerId $ownerId, Reason $reason, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, (string)$ownerId, $reason->value, $occurredAt);
    }

    public static function create(Identifier $id, \DateTimeImmutable $occurredAt, string $assetId, string $ownerId, string $reason): self
    {
        return new self($assetId, $ownerId, $reason, $occurredAt, $id);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}