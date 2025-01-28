<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use SoftwareArchetypes\Availability\SimpleAvailability\Common\Identifier;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\Reason;

final readonly class AssetWithdrawalRejected extends BaseDomainEvent
{
    private const string TYPE = "AssetWithdrawalRejected";

    private function __construct(
        public string $assetId,
        public string $reason,
        ?\DateTimeImmutable $occurredAt = null,
        ?Identifier $id = null
    ) {
        parent::__construct($id, $occurredAt);
    }

    public static function from(AssetId $assetId, Reason $reason, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, $reason->value, $occurredAt);
    }

    public static function create(Identifier $id, \DateTimeImmutable $occurredAt, string $assetId, string $reason): self
    {
        return new self($assetId, $reason, $occurredAt, $id);
    }

    public static function dueToAssetLocked(AssetId $assetId, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, Reason::ASSET_LOCKED_REASON->value, $occurredAt);
    }

    public static function dueToMissingAssetWith(AssetId $assetId, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, Reason::ASSET_IS_MISSING_REASON->value, $occurredAt);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
