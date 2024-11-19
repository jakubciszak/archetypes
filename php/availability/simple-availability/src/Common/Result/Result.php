<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common\Result;

use Munus\Control\Either;

/**
 * @template L
 * @template R
 * @template-extends Either<L, R>
 * @param R $value
 * @return Either<L, R>
 */
function success($value): Either
{
    return Either::right($value);
}

/**
 * @template L
 * @template R
 * @template-extends Either<L, R>
 * @param L $value
 * @return Either<L, R>
 */
function failure($value): Either
{
    return Either::left($value);
}
