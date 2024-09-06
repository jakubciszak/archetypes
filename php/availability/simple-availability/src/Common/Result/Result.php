<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common\Result;

use LogicException;

/**
 * @template F
 * @template S
 * @param S $value
 * @return Result<F, S>
 */
function success($value): Result {
    return new Success($value);
}

/**
 * @template F
 * @template S
 * @param F $value
 * @return Result<F, S>
 */
function failure($value): Result {
    return new Failure($value);
}

/**
 * @template F
 * @template S
 */
interface Result {
    /**
     * @return bool
     */
    public function success(): bool;

    /**
     * @return bool
     */
    public function failure(): bool;

    /**
     * @return S
     * @throws LogicException
     */
    public function getSuccess(): Result;

    /**
     * @return F
     * @throws LogicException
     */
    public function getFailure(): Result;
}