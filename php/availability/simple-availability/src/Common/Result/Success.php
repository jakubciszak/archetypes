<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common\Result;

use LogicException;

/**
 * @template F
 * @template S
 * @implements Result<F, S>
 */
class Success implements Result
{
    /**
     * @var S
     */
    private mixed $success;

    /**
     * @param S $success
     */
    public function __construct($success)
    {
        $this->success = $success;
    }

    public function success(): bool
    {
        return true;
    }

    public function failure(): bool
    {
        return false;
    }

    /**
     * @return S
     */
    public function getSuccess(): Result
    {
        return $this->success;
    }

    /**
     * @throws LogicException
     */
    public function getFailure(): Result
    {
        throw new \LogicException('Cannot get failure from a success result.');
    }
}