<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Events;

use Ramsey\Uuid\Uuid;
use SoftwareArchetypes\Availability\SimpleAvailability\Common\Equatable;
use SoftwareArchetypes\Availability\SimpleAvailability\Domain\AssetId;

readonly class AssetWithdrawn extends BaseDomainEvent implements Equatable
{
    private const string TYPE = 'AssetWithdrawn';

    private function __construct(private string $assetId, \DateTimeImmutable $occurredAt = null, ?Uuid $id = null)
    {
        parent::__construct($id, $occurredAt);
    }

    public static function from(AssetId $assetId, \DateTimeImmutable $occurredAt): self
    {
        return new self((string)$assetId, $occurredAt);
    }

    public static function create(Uuid $id, \DateTimeImmutable $occurredAt, AssetId $assetId): self
    {
        return new self((string)$assetId, $occurredAt, $id);
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getAssetId(): string
    {
        return $this->assetId;
    }

    public function equals(Equatable $other): bool
    {
        if ($other === $this) {
            return true;
        }
        if (!($other instanceof self)) {
            return false;
        }
        return $other->assetId === $this->assetId;
    }
}
