<?php

namespace Tests\Domain\Fixtures;

abstract class AssetBuilder
{
    protected function executeOnAsset(\Closure $assetFunction): static
    {
        return $this;
    }
}
