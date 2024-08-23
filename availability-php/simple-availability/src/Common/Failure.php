<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common;

class Failure implements Result
{
    private mixed $failure;

    public function __construct(mixed $failure)
    {
        $this->failure = $failure;
    }

    public function success(): bool
    {
        return false;
    }

    public function failure(): bool
    {
        return true;
    }

    public function getSuccess(): mixed
    {
        throw new \LogicException("No success value in Failure result");
    }

    public function getFailure(): mixed
    {
        return $this->failure;
    }

    public static function createSuccess(mixed $value): Result
    {
        throw new \LogicException("Cannot create Success from Failure");
    }

    public static function createFailure(mixed $value): Result
    {
        return new self($value);
    }

    public function biMap(callable $successMapper, callable $failureMapper): Result
    {
        return new self($failureMapper($this->getFailure()));
    }

    public function map(callable $mapper): Result
    {
        return $this;
    }

    public function mapFailure(callable $mapper): Result
    {
        return new self($mapper($this->getFailure()));
    }

    public function peek(callable $successConsumer, callable $failureConsumer): Result
    {
        $failureConsumer($this->getFailure());
        return $this;
    }

    public function peekSuccess(callable $successConsumer): Result
    {
        return $this;
    }

    public function peekFailure(callable $failureConsumer): Result
    {
        $failureConsumer($this->getFailure());
        return $this;
    }

    public function ifSuccessOrElse(callable $successMapping, callable $failureMapping): mixed
    {
        return $failureMapping($this->getFailure());
    }

    public function flatMap(callable $mapping): Result
    {
        return $this;
    }

    public function fold(callable $leftMapper, callable $rightMapper): mixed
    {
        return $leftMapper($this->getFailure());
    }
}
