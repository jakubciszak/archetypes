<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use Ramsey\Uuid\Uuid;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\Reason;

final readonly class AssetActivationRejected extends BaseDomainEvent
{
    private const string TYPE = "AssetActivationRejected";

    private function __construct(
        public string $assetId,
        public string $reason,
        ?\DateTimeImmutable $occurredAt = null,
        ?Uuid $id = null
    ) {
        parent::__construct($id, $occurredAt);
    }

    public static function from(AssetId $assetId, Reason $reason, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, $reason->value, $occurredAt);
    }

    public static function create(Uuid $id, \DateTimeImmutable $occurredAt, string $assetId, string $reason): self
    {
        return new self($assetId, $reason, $occurredAt, $id);
    }

    //add dueToMissingAssetWith method
    public static function dueToMissingAssetWith(AssetId $assetId, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, Reason::ASSET_IS_MISSING_REASON->value, $occurredAt);
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}