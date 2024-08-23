<?php

namespace SoftwareArchetypes\Availability\SimpleAvailability\Common;

final class Success implements Result
{
    private mixed $success;

    public function __construct(mixed $success)
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

    public function getSuccess(): mixed
    {
        return $this->success;
    }

    public function getFailure(): mixed
    {
        throw new \LogicException("No failure value in Success result");
    }

    public static function createSuccess(mixed $value): Result
    {
        return new self($value);
    }

    public static function createFailure(mixed $value): Result
    {
        throw new \LogicException("Cannot create Failure from Success");
    }

    public function biMap(callable $successMapper, callable $failureMapper): Result
    {
        return new self($successMapper($this->getSuccess()));
    }

    public function map(callable $mapper): Result
    {
        return new self($mapper($this->getSuccess()));
    }

    public function mapFailure(callable $mapper): Result
    {
        return $this;
    }

    public function peek(callable $successConsumer, callable $failureConsumer): Result
    {
        $successConsumer($this->getSuccess());
        return $this;
    }

    public function peekSuccess(callable $successConsumer): Result
    {
        $successConsumer($this->getSuccess());
        return $this;
    }

    public function peekFailure(callable $failureConsumer): Result
    {
        return $this;
    }

    public function ifSuccessOrElse(callable $successMapping, callable $failureMapping): mixed
    {
        return $successMapping($this->getSuccess());
    }

    public function flatMap(callable $mapping): Result
    {
        return $mapping($this->getSuccess());
    }

    public function fold(callable $leftMapper, callable $rightMapper): mixed
    {
        return $rightMapper($this->getSuccess());
    }
}
