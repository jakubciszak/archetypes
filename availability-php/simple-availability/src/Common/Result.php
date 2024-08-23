<?php
namespace SoftwareArchetypes\Availability\SimpleAvailability\Common;

interface Result
{
    public function success(): bool;

    public function failure(): bool;

    public function getSuccess(): mixed;

    public function getFailure(): mixed;

    public static function createSuccess(mixed $value): Result;

    public static function createFailure(mixed $value): Result;

    public function biMap(callable $successMapper, callable $failureMapper): Result;

    public function map(callable $mapper): Result;

    public function mapFailure(callable $mapper): Result;

    public function peek(callable $successConsumer, callable $failureConsumer): Result;

    public function peekSuccess(callable $successConsumer): Result;

    public function peekFailure(callable $failureConsumer): Result;

    public function ifSuccessOrElse(callable $successMapping, callable $failureMapping): mixed;

    public function flatMap(callable $mapping): Result;

    public function fold(callable $leftMapper, callable $rightMapper): mixed;
}
