<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common\Result;

use LogicException;

/**
 * @template F
 * @template S
 * @implements Result<F, S>
 */
class Failure implements Result
{
    /**
     * @var F
     */
    private $failure;

    /**
     * @param F $failure
     */
    public function __construct($failure) {
        $this->failure = $failure;
    }

    public function success(): bool {
        return false;
    }

    public function failure(): bool {
        return true;
    }

    /**
     * @throws LogicException
     */
    public function getSuccess(): Result
    {
        throw new \LogicException('Cannot get success from a failure result.');
    }

    /**
     * @return F
     */
    public function getFailure(): Result
    {
        return $this->failure;
    }
}